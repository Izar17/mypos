<?php

namespace Modules\NsPrintAdapter\Services;

use App\Classes\Hook;
use App\Exceptions\NotFoundException;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\Register;
use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenCategory;
use Modules\NsGastro\Models\Order as ModelsOrder;
use Modules\NsPrintAdapter\Events\AfterPrintJobsCreatedEvent;
use Modules\NsPrintAdapter\Libraries\PrintJob;
use Modules\NsPrintAdapter\Models\Printer;

class PrintService
{
    private $productFilterMapping = [
        'kitchen'           =>  [
            'print_status'      =>  'meal_printed',
            'cooking_status'    =>  'pending'
        ],
        'kitchen-canceled'  =>  [
            'print_status'      =>  'meal_cancelation_printed',
            'cooking_status'    =>  'canceled'
        ],
    ];

    public function getKitchenJobs( ModelsOrder $order, string $document )
    {
        /**
         * Not really happy with this :\
         */
        $kitchens   = $this->getKitchensForUnprintedProducts( 
            order: $order, 
            filter: [
                $this->productFilterMapping[ $document ][ 'print_status' ] => false,
                'cooking_status' => $this->productFilterMapping[ $document ][ 'cooking_status' ],
            ]
        );

        $printJobs  =   $kitchens->map( function( $kitchen ) use( $document, $order ) {
            return $kitchen->printers->map( function( $kitchenPrinter ) use( $kitchen, $document, $order ) {
                return $this->getJobs( 
                    printers: collect( [ $kitchenPrinter->printer ] ), 
                    document: $document, 
                    reference_id: $order->id, 
                    data: [
                        'kitchen' =>  $kitchen,
                        'categories' => $kitchen->categories->map( fn( $kitchenCategory ) => $kitchenCategory->category_id )->toArray()
                    ]
                );
            });
        })->flatten();

        AfterPrintJobsCreatedEvent::dispatch( $printJobs->toArray() );

        return $printJobs;
    }

    /**
     * @deprecated ?
     */
    public function getDocumentReceipts( $resources, $document = 'sale' ): Collection
    {     
        $address            = ns()->option->get('ns_pa_server_address');
        $defaultPrinters    = $this->getDefaultPrinters();

        /**
         * In case the printer is not a proper printer
         * we'll throw an explicit exception.
         */
        if ( ! $defaultPrinters->count() === 0 ) {
            throw new NotFoundException( __( 'No default printer is defined.' ) );
        }

        return $resources->map( function( $resource ) use ( $document, $defaultPrinters, $address ) {            
            return collect( ! $resource[ 'printers' ]->isEmpty() ? $resource[ 'printers' ] : $defaultPrinters )->map( function( $printer ) use ( $address, $resource, $document  ) {
                return [
                    'content'   =>  $this->getDocumentOutput(
                        resource: $resource,
                        printer: $printer,
                        document: $document
                    ),
                    'pixelize'  =>  ns()->option->get( 'ns_pa_convert_to_image' ) === 'yes',
                    'address'   =>  $address,
                ];
            });
        })->flatten(1);
    }

    /**
     * @deprecated ?
     */
    public function getDefaultPrinters( $printerId = null ): Collection
    {
        $registerId         = request()->query('cash-register');

        /**
         * if a specific printer is provided
         * we should use it instead of a default scenario.
         */
        if ( $printerId !== null ) {
            $printer    =   Printer::find( $printerId );
        } else {
            if (! empty($registerId) && $registerId !== 'null') {
                $cashRegister = Register::find($registerId);
    
                if (! $cashRegister instanceof Register) {
                    throw new NotFoundException(__('Unable to find the requested cash register.'));
                }
    
                $printer    =   Printer::enabled()->find( $cashRegister->printer_id );
            } else {
                $printer     =   Printer::enabled()->isDefault()->first();
            }
        }

        return collect([ $printer ]);
    }

    /**
     * Returns a document output.
     */
    public function getDocumentOutput( $resource, Printer $printer, string $document, $data = [] ): string
    {
        $convertToImage  = ns()->option->get( 'ns_pa_convert_to_image' ) === 'yes';

        return $convertToImage ? 
            $this->getHTMLOutput( $resource, $printer, $document, $data ) :
            $this->getRawOutput( $resource, $printer, $document, $data );
    }

    public function getPrinterInterface( Printer $printer ): string
    {
        return match( $printer->interface ) {
            Printer::INTERFACE_USBSERIAL   =>   'Printer:' . $printer->name,
            Printer::INTERFACE_PARALLEL     =>  'tcp:' . $printer->name,
        };
    }

    /**
     * @deprecated ?
     */
    public function canProceedPrinting( Order $order, $callback )
    {
        $printAllowedFor = ns()->option->get('ns_pos_printing_enabled_for', 'partially_paid_orders');
        
        switch ($printAllowedFor) {
            case 'all_orders':
                return $callback( $order );
            break;
            case 'partially_paid_orders':
                if (in_array($order->payment_status, [Order::PAYMENT_PAID, Order::PAYMENT_PARTIALLY])) {
                    return $callback( $order );
                }
            break;
            case 'only_paid_orders':
                if (in_array($order->payment_status, [Order::PAYMENT_PAID])) {
                    return $callback( $order );
                }
            break;
        }
    }

    /**
     * Return a Base64 version of a receipt
     * that can then be sent to Nexo Print Server.
     */
    public function getHTMLOutput( $resource, Printer $printer, string $document = 'sale', $data = [] ): string
    {
        if ( ns()->option->get( 'ns_pa_generate_image' ) === 'yes' ) {
            $output = $this->getBase64Output( $resource, $printer, $document, $data );
        } else {
            $output = $this->getHTMLUrlOutput( $resource, $printer, $document, $data );
        }        

        return $output();
    }

    public function getBase64Output( $resource, $printer, $document, $data )
    {
        return match( $document ) {
            'sale'      =>  fn() => View::make( 'NsPrintAdapter::receipt.base64.sale', [
                'resource' => $resource,
                'printer'   =>  $printer,
                'data'  =>  $data,
            ])->render(),
            'refund'    =>  fn() => View::make( 'NsPrintAdapter::receipt.base64.refund', [
                'resource' => $resource,
                'printer' => $printer,
                'data'  =>  $data,
            ])->render(),
            'kitchen'   =>  fn() => View::make( 'NsPrintAdapter::receipt.base64.kitchen', [
                'resource' => $resource,
                'printer' => $printer,
                'data'  =>  $data,
            ])->render(),
            'payment'   =>  fn() => View::make( 'NsPrintAdapter::receipt.base64.payment', [
                'resource' => $resource,
                'printer' => $printer,
                'data'  =>  $data,
            ])->render(),
        };
    }

    public function getHTMLUrlOutput( $resource, Printer $printer, string $document = 'sale' ): Closure
    {
        return match( $document ) {
            'sale'      =>  fn() => View::make( 'NsPrintAdapter::receipt.images.wrapper', [
                'url'       =>  ns()->route( 'nps-image.sale-receipt', [ 'reference_id' => $resource ]),
                'printer'   =>  $printer
            ])->render(),
            'refund'    =>  fn() => View::make( 'NsPrintAdapter::receipt.images.wrapper', [
                'url'   =>  ns()->route( 'nps-image.refund-receipt', [ 'reference_id' => $resource ] ),
                'printer' => $printer,
            ])->render(),
            'kitchen'   =>  fn() => View::make( 'NsPrintAdapter::receipt.images.wrapper', [
                'url'   =>  ns()->route( 'nps-image.kitchen-receipt', [ 'reference_id' => $resource ] ),
                'printer' => $printer,
            ])->render(),
            'payment'   =>  fn() => View::make( 'NsPrintAdapter::receipt.images.wrapper', [
                'url'   =>  ns()->route( 'nps-image.payment-receipt', [ 'reference_id' => $resource ] ),
                'printer' => $printer,
            ])->render(),
        };
    }

    /**
     * Will only return the receipt output
     * regardless of what printer is used
     */
    public function getRawOutput( $resource, Printer $printer, string $document = 'sale', $data = [] ): string
    {
        return View::make( 
            $this->getDocumentPath( $document ), 
            compact( 'resource', 'printer', 'document', 'data' )
        )->render();
    }

    /**
     * Define the print document
     * path based on the code provided.
     */
    public function getDocumentPath( string $code ): string {
        return Hook::filter( 'ns-pa.print-document', match( $code ) {
            'sale'              =>  'NsPrintAdapter::receipt.nps',
            'refund'            =>  'NsPrintAdapter::receipt.refund-nps',
            'payment'           =>  'NsPrintAdapter::receipt.payment-nps',
            'kitchen'           =>  'NsPrintAdapter::receipt.kitchen-nps',
            'kitchen-canceled'  =>  'NsPrintAdapter::receipt.kitchen-canceled-nps',
            default             =>  'NsPrintAdapter::receipt.not-defined-nps'
        }, $code );
    }

    public function nexting($values, $replacement = ' ', $limit = null, $ratio = 1)
    {
        if ($limit == null) {
            $limit = ns()->option->get('ns_pa_characters_limit', 48);
        }

        $length = 0;
        $countString = count($values);

        foreach ($values as $val) {
            $length += (count(preg_split('//u', $val, -1, PREG_SPLIT_NO_EMPTY)) * $ratio);
        }

        $fill = '';
        for ($i = 0; $i < $limit - $length; $i++) {
            $fill .= $replacement;
        }

        if (count($values) == 0) {
            return $fill;
        }

        $spaceBetweenValues = floor($length / count($values));

        $finalString = '';
        foreach ($values as $index => $value) {
            if ($index == $countString - 1) {
                $finalString .= $value;
            } else {
                $finalString .= $value.$fill;
            }
        }

        return $this->special_removal($finalString);
    }

    public function special_removal( string $string ): string
    {
        $search = [
            '<'     =>  '&#60;',
            '>'     =>  '&#62;',
            '&'     =>  '&#38;',
            '\''    =>  '&#39;',
            '"'     =>  '&#34;',
        ];

        foreach ($search as $needle => $replace) {
            $string = str_replace($needle, $replace, $string);
        }

        return $string;
    }

    public function buildingLines( string $col1,  string $col2 ): array 
    {
        $col1_lines = preg_split('/$\R?^/m', $col1);
        $col2_lines = preg_split('/$\R?^/m', $col2);
        $finalBuild = [];

        /**
         * We would like to use the hight table number
         */
        for ($i = 0; $i < (count($col1_lines) > count($col2_lines) ? count($col1_lines) : count($col2_lines)); $i++) {
            $finalBuild[] = [trim(@$col1_lines[$i]), trim(@$col2_lines[$i])];
        }

        return $finalBuild;
    }

    /**
     * Text to EsCText
     */
    public function textToEsc( string $string): array
    {
        $col1_lines = preg_split('/$\R?^/m', $string);
        $finalBuild = [];

        /**
         * We would like to use the hight table number
         */
        for ($i = 0; $i < count($col1_lines); $i++) {
            $finalBuild[] = trim(@$col1_lines[$i]);
        }

        return $finalBuild;
    }

    public function __fill( string $char, string $maxLetter): string
    {
        $finalString = '';

        for ($i = 0; $i < $maxLetter; $i++) {
            $finalString .= $char;
        }

        return $finalString;
    }

    /**
     * Populate a line with a string and fill
     * with the place holder
     */
    public function __populate( string $string, int $max, array $config = [
        'align' =>  'left',
        'fill'  =>  ' ',
    ]): string
    {
        extract($config);
        $strLen = strlen($string);
        $toPopulate = $max - $strLen;

        return $string.$this->__fill($fill, $toPopulate);
    }

    /**
     * Check if a string or an array
     * of string will overflow the provided with
     */
    public function __willOverFlow( array $row, array $widthPerColumn, int $maxLetter): int
    {
        /**
         * let's check if string
         * will overflow
         */
        $maximumRowOverflow = 0;

        foreach ($row as $__index => $col) {
            if (is_array($col)) {
                $col = $this->__getRealColString(compact('col', '__index', 'widthPerColumn', 'maxLetter'));
            }

            /**
             * Make the placeholder length
             * per column automatic
             */
            $placeholderLengthPerColumn = floor(($widthPerColumn[$__index] * $maxLetter) / 100);
            $maximumLines = round(strlen($col) / $placeholderLengthPerColumn);

            /**
             * Reassign the maxium line only
             * if it's greater
             */
            $maximumRowOverflow = $maximumLines > $maximumRowOverflow ? $maximumLines : $maximumRowOverflow;
        }

        return $maximumRowOverflow;
    }

    /**
     * Get Real row string, including extrat fields
     */
    public function __getRealColString( array $data): string
    {
        extract($data);

        $resultString = '';
        foreach ($col as $colString) {
            $result = $this->__populate($colString, floor(($widthPerColumn[$__index] * $maxLetter) / 100), [
                'align'     =>  'left',
                'fill'      =>  isset($fillWith) ? $fillWith : ' ',
            ]);

            $resultString .= $result;
        }

        return $resultString;
    }

    /**
     * render lines
     */
    public function __renderLines( array $data): string
    {
        extract($data);

        $colString = isset($col) ? $col : $colString;

        /**
         * Make the placeholder length
         * per column automatic
         */
        $placeholderLengthPerColumn = floor(($widthPerColumn[$__index] * $maxLetter) / 100);

        if (strlen($colString) > $placeholderLengthPerColumn) {
            $rawStr = (substr($colString, $rowId * ($placeholderLengthPerColumn - 1), $placeholderLengthPerColumn - 1));
            $rawStr .= ' '; // to add a space between the text and the next column
        } elseif ($rowId === 0 && strlen($colString) <= $placeholderLengthPerColumn) {
            $rawStr = trim($colString);
        } else {
            $rawStr = '';
        }

        $str = $this->__populate($rawStr, $placeholderLengthPerColumn, [
            'align'     =>  'left',
            'fill'      =>  ' ',
        ]);

        return $str;
    }

    /**
     * Create toEscTable
     */
    public function toEscTable( array $rawTable, array $config = [
        'bodyLines'     =>  true,
        'maxLetter'     =>  150,
        'fillWith'      =>  ' ',
    ]): string
    {
        extract($config);

        $totalColumns = count($rawTable[0]);
        $placeholderLengthPerColumn = ceil($maxLetter / $totalColumns);
        $finalString = '';
        $widthPerColumn = [];

        foreach ($rawTable as $index => $row) {

            /**
             * first row is the header
             */
            if ($index === 0) {
                $finalString .= $this->__fill('-', $maxLetter)."\r\n";

                $totalStringPerCol = array_map(function ($col) {
                    return strlen($col['title']);
                }, $row);

                $totalUsedString = array_sum($totalStringPerCol);
                $maxDefinedWidth = 0;
                $totalAutoWidth = 0;

                foreach ($row as $__index => $col) {

                    /**
                     * Save defined width
                     * or count auto columns
                     */
                    if (is_numeric(@$col['width'])) {
                        $maxDefinedWidth += $col['width'];
                    } else {
                        $totalAutoWidth++;
                    }
                }

                /**
                 * let's calculate the auto
                 * width for columns
                 */
                $availableAutoWidth = 100 - $maxDefinedWidth;
                $autoWidth = $totalAutoWidth === 0 ? 0 : floor($availableAutoWidth / $totalAutoWidth);

                foreach ($row as $__index => $col) {
                    if ($col['width'] === 'auto') {
                        $widthPerColumn[] = $autoWidth;
                    } else {
                        $widthPerColumn[] = floatval($col['width']);
                    }
                }

                foreach ($row as $__index => $col) {
                    $str = $this->__populate($col['title'], ($widthPerColumn[$__index] * $maxLetter) / 100, [
                        'align'     =>  @$col['align'] ?: 'left',
                        'fill'      =>  $fillWith,
                    ]);

                    $finalString .= $str;
                }

                $finalString .= "\r\n";

                $finalString .= $this->__fill('-', $maxLetter)."\r\n";
            } else {

                /**
                 * let's check if string
                 * will overflow
                 */
                $maximumRowOverflow = $this->__willOverFlow($row, $widthPerColumn, $maxLetter);

                /**
                 * According to the defined overflow
                 * let's populate the row
                 */
                for ($rowId = 0; $rowId <= $maximumRowOverflow; $rowId++) {
                    $rendered = false;

                    foreach ($row as $__index => $col) {

                        /**
                         * let's render each column and make sure
                         * a column with an array is also rendered
                         */
                        if (is_array($col)) {
                            $col = $this->__getRealColString(compact('col', 'widthPerColumn', 'maxLetter', 'fillWith', '__index'));
                        }

                        $rendered = $this->__renderLines(compact('widthPerColumn', 'maxLetter', 'col', '__index', 'rowId', 'finalString'));

                        if ($rendered !== false) {
                            $finalString .= $rendered;
                        }
                    }

                    if ($rendered) {
                        $finalString .= "\r\n";
                    }
                }

                if ($bodyLines) {
                    $finalString .= $this->__fill('-', $maxLetter)."\r\n";
                }
            }

            /**
             * if were closing the table
             * checking the last index
             */
            if ($index == count($rawTable) - 1) {
                $finalString .= $this->__fill('-', $maxLetter)."\r\n";
            }
        }

        return $finalString;
    }

    /**
     * Use the printers array to create or refresh
     * existing printers on the database.
     */
    public function refreshPrinters( array $printers ): array
    {
        foreach( $printers as $printer ) {
            $newPrinter     =   Printer::where( 'argument', $printer[ 'shareName' ] ?? $printer[ 'name' ] )
                ->first();

            if ( ! $newPrinter instanceof Printer ) {
                $newPrinter                 =   new Printer;
                $newPrinter->identifier     =   Str::uuid();
                $newPrinter->status         =   Printer::DISABLED;
                $newPrinter->characterset   =   'PC850_MULTILINGUAL'; // by default
                $newPrinter->author         =   Auth::id();
            }

            $newPrinter->argument       =   $printer[ 'shareName' ] ?? $printer[ 'name' ];
            $newPrinter->name           =   $printer[ 'shareName' ] ?? $printer[ 'name' ];
            $newPrinter->interface      =   'usb_serial';
            $newPrinter->save();
        }

        return [
            'status'    =>  'success',
            'message'   =>  __m( 'The printer was refreshed', 'NsPrintAdapter' )
        ];
    }

    public function getKitchensForUnprintedProducts( Order $order, $filter = [] )
    {
        $order->load( 'products.product' );

        $categories     =   $order->products()
            ->where( function( $query ) use ( $filter ) {
                foreach( $filter as $key => $value ) {
                    $query->where( $key, $value );
                }
            })
            ->get()
            ->map( function( $item ) {
                return $item->product->category_id;
            })->unique();

        $kitchensIds   =   KitchenCategory::whereIn( 'category_id', $categories )->get()->map( function( $item ) {
            return $item->kitchen_id;
        })->unique();

        return Kitchen::whereIn( 'id', $kitchensIds )->with([ 'printers.printer', 'categories' ])->get();
    }

    /**
     * Returns a list of printers
     */
    public function getJobs( $printers, $document, $reference_id, $data = [] )
    {
        $documentClassMapping   =   [
            'sale'              =>  Order::class,
            'kitchen'           =>  ModelsOrder::class,
            'kitchen-canceled'  =>  ModelsOrder::class,
            'payment'           =>  OrderPayment::class,
            'refund'            =>  OrderRefund::class,
        ];

        if ( ! isset( $documentClassMapping[ $document ] ) ) {
            throw new NotFoundException( __( 'The requested document is not supported.' ) );
        }

        return $this->getPrintResource(
            reference_id: $reference_id,
            document: $document,
            printers: $printers,
            reference_type: $documentClassMapping[ $document ],
            data: $data
        );
    }

    public function getPrintableProducts( Order $order, $categories = [], $filter = [] )
    {
        return $order->products()->where( function( $query ) use ( $filter, $categories ) {
            foreach( $filter as $key => $value ) {
                $query->where( $key, $value );
            }
            
            $query->whereIn( 'product_category_id', $categories );
        })->get();
    }

    /**
     * returns valid printing resource.
     */
    public function getPrintResource( $reference_id, $document, $printers, $reference_type, $data = [] )
    {
        return $printers->map( function( $printer ) use ( $reference_id, $document, $reference_type, $data ) {
            if ( ! $printer instanceof Printer ) {
                throw new Exception( 
                    sprintf(
                        __m( 'The printer provided for kitchen "%s" is not valid or might not exists.', 'NsPrintAdapter' ),
                        $data[ 'kitchen' ]->name
                    )
                );      
            }

            return new PrintJob(
                printer: $printer,
                content: $this->getDocumentOutput( 
                    resource: $reference_type::findOrFail( $reference_id ), 
                    printer: $printer, 
                    document: $document,
                    data: $data
                )
            );
        });
    }
}
