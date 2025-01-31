<?php

namespace Modules\NsGastro\Services;

use App\Events\OrderAfterUpdatedEvent;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Modules\NsGastro\Events\GastroOrderAfterMovedEvent;
use Modules\NsGastro\Events\KitchenAfterUpdatedOrderEvent;
use Modules\NsGastro\Events\TableAfterUpdatedEvent;
use Modules\NsGastro\Models\Area;
use Modules\NsGastro\Models\Order as ModelsOrder;
use Modules\NsGastro\Models\OrderProduct;
use Modules\NsGastro\Models\Table;
use Modules\NsGastro\Models\TableSession;
use Illuminate\Support\Facades\DB;

class TableService
{
    /**
     * fetch order assigned
     * to a specific table
     *
     * @param  Table  $table
     * @return Collection
     */
    public function getTableOrders(Table $table, $range_starts = null, $range_ends = null)
    {
        if ((bool) ns()->option->get('ns_gastro_enable_table_sessions', false)) {
            $range_starts = $range_starts === null ? ns()->date->copy()->startOfDay()->toDateTimeString() : $range_starts;
            $range_ends = $range_ends === null ? ns()->date->copy()->endOfDay()->toDateTimeString() : $range_ends;

            $session = $table
                ->sessions()
                ->active()
                ->first();
                

            if ($session instanceof TableSession) {
                
                return $session
                    ->orders()
                    ->where('created_at', '>=', $range_starts)
                    ->where('created_at', '<=', $range_ends)
                    ->orderBy('id', 'desc')
                    ->with('products.modifiers', 'customer', 'user')
                    ->get();

                    dd( $session );
            }

            return collect([]);
        } else {
            $range_starts = $range_starts === null ? ns()->date->copy()->startOfDay()->toDateTimeString() : $range_starts;
            $range_ends = $range_ends === null ? ns()->date->copy()->endOfDay()->toDateTimeString() : $range_ends;

            return $table->orders()
                ->orderBy('id', 'desc')
                ->where('created_at', '>=', $range_starts)
                ->where('created_at', '<=', $range_ends)
                ->with('products.modifiers', 'customer', 'user')
                ->get();
        }
    }

    /**
     * Will search table matching
     * the provided query
     *
     * @param string table name
     * @return array
     */
    public function searchTables( string | null $name, int $ignore_table_id = 0)
    {
        return Table::where('name', 'like', '%'.$name.'%')
            ->where( 'id', '<>', $ignore_table_id )
            ->get();
    }

    /**
     * Change the table status
     *
     * @param  Table  $table
     * @param 'busy' | 'available'  $status
     * @return void
     */
    public function changeTableAvailability(Table $table, string $status)
    {
        /**
         * from now, we should only change the status of a 
         * table if we're sure the table session is enabled.
         */
        if (in_array($status, ['available', 'busy']) && ( bool ) ns()->option->get( 'ns_gastro_enable_table_sessions' ) ) {
            /**
             * if a session has been created, while closing
             * we'll make sure to get an active session.
             */
            if ( $status === 'available' ) {
                $table->busy = false;

                $session = $this->getActiveTableSession($table);

                if ($session instanceof TableSession) {
                    $this->closeTableSession($session);
                }

            } else {

                $table->busy = true;

                $this->startTableSession( $table );
            }

            $table->save();

            TableAfterUpdatedEvent::dispatch($table);

            return [
                'status'    =>  'success',
                'message'   =>  __m('The table availability has been updated.', 'NsGastro'),
            ];
        }

        throw new NotAllowedException( __m('The table availability can\'t be updated.', 'NsGastro') );
    }

    public function getTableSessions(Table $table, $rangeStarts, $rangeEnds)
    {
        return $table->sessions()
            ->where('session_starts', '>=', $rangeStarts ?: ns()->date->getNow()->startOfDay()->toDateTimeString())
            ->where('session_starts', '<=', $rangeEnds ?: ns()->date->getNow()->endOfDay()->toDateTimeString())
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($session) {
                $session->ordersCount = $session->orders()->count();

                return $session;
            });
    }

    /**
     * Closes a table session
     * and make the table available by the same way
     *
     * @param  Tablesession  $session
     * @return array $response
     */
    public function closeTableSession(TableSession $session)
    {
        /**
         * Let's now save the session
         * as it should be saved.
         */
        $session->active = false;
        $session->session_ends = ns()->date->toDateTimeString();
        $session->save();

        return [
            'status'    =>  'success',
            'message'   =>  __('The session has been successfully updated.'),
        ];
    }

    /**
     * Move an order from one table to another
     *
     * @param  Order  $order
     * @param  int  $table_id new destination table
     * @return array result
     */

     public function changeTable(Order $order, $table_id)
     {
         $prevOrder = clone $order;
     
         if ($order->table_id === $table_id) {
             throw new NotAllowedException(__m('The order is already assigned to this table.', 'NsGastro'));
         }
     
         $previousTable = Table::find($order->table_id);
         $table = Table::find($table_id);
     
         if (! $table instanceof Table) {
             throw new NotFoundException(__m('Unable to find the destination table.', 'NsGastro'));
         }
     
         // Verify if the table has an order and if that order customer matches the new customer
         if (! (bool) $table->allow_multi_clients) {
             $session = $this->getActiveTableSession($table);
     
             if ($session instanceof TableSession) {
                 $session->orders->each(function ($_order) use ($order) {
                     if ($_order->customer_id !== $order->customer_id) {
                         throw new NotAllowedException(__m('This table doesn\'t allow multiple customers', 'NsGastro'));
                     }
                 });
             }
         }
     
         // Check if the destination table has an ongoing session, if not, open a new session for that table
         $session = $this->startTableSession($table, true);
     
         // Assign the order to the table and to the session
         $order->table_id = $table_id;
         $order->gastro_table_session_id = $session->id;
         $order->save();
     
         // Update the nexopos_orders table
         DB::table('nexopos_orders')
             ->where('id', $order->id)
             ->update([
                 'table_id' => $table_id,
                 'table_name' => $table->name
             ]);
     
         // Set the previous table to free
         $previousTable->status = 'free';
         $previousTable->save();
     
         // Update previous table's status
         $this->closeTableSessionIfFreed($previousTable);
     
         // Trigger events
         event(new OrderAfterUpdatedEvent(
             newOrder: $order,
             prevOrder: $prevOrder,
             fields: compact('table_id')
         ));
         
         GastroOrderAfterMovedEvent::dispatch($previousTable, $table, $order);
     
         return [
             'status' => 'success',
             'message' => sprintf(__m('The order has been successfully moved to %s', 'NsGastro'), $table->name),
         ];
     }
     

    public function saveTable( $order, $fields )
    {
        if (isset($fields['table']) && isset($fields['table']['selected']) && $fields['table']['selected'] === true) {
            
            $table = Table::findOrFail( $fields['table']['id']);
            $area = Area::find($table->area_id);

            $order->table_id = $table->id;
            $order->table_name = $table->name;
            $order->area_name = $area instanceof Area ? $area->name : null;
            $order->area_id = $table->area_id;
            $order->seats = $table->seats ?? 0;
            $order->gastro_order_status = $fields['gastro_order_status'] ?? ModelsOrder::COOKING_PENDING;

            /**
             * We might check if all products
             * that has been submitted are premarked
             * as ready
             */
            $order->products->each(function ($orderProduct) {
                if ($orderProduct->product instanceof Product && (bool) $orderProduct->product->skip_cooking) {
                    $orderProduct->cooking_status = OrderProduct::COOKING_READY;
                    $orderProduct->save();
                }
            });

            KitchenAfterUpdatedOrderEvent::dispatch($order);
            $session = self::startTableSession( $table );

            $order->gastro_table_session_id = $session instanceof TableSession ? $session->id : null;
            $order->save();
        }
    }

    public function closeTableSessionIfFreed( Table | null $table)
    {
        if ( $table instanceof Table ) {
            /**
             * we'll check the table sessions orders
             * and see if either the sessions has no, unpaid or hold order
             * if that's the case, we'll close the session
             */
            $session = $this->getActiveTableSession($table);
    
            if (! $session instanceof TableSession) {
                return;
            }
    
            $session->load('orders');
    
            $hasUnpaidOrders    = $session->orders()->where( 'payment_status', Order::PAYMENT_UNPAID )->count();
            $hasHoldOrders      = $session->orders()->where( 'payment_status', Order::PAYMENT_HOLD )->count();
            $hasOrders          = $session->orders()->count();
    
            if ( $hasOrders === 0 || $hasUnpaidOrders === 0 || $hasHoldOrders === 0 ) {
                $this->closeTableSession($session);
            }
        }
    }

    /**
     * Will start a table session
     * using an order
     *
     * @param  Order  $order
     * @return TableSession | null
     */
    public function startTableSessionUsingOrder( Order $order ): null | TableSession
    {
        if ( empty( $order->table_id  ) ) {
            return null;
        }

        $table = Table::find($order->table_id);

        if (! $table instanceof Table) {
            throw new NotFoundException(__m('Unable to find the destination table.', 'NsGastro'));
        }

        return $this->startTableSession(
            table: $table,
            silent: true
        );
    }

    /**
     * Will start a table session
     *
     * @param  Table  $table
     * @param  bool  $silent
     * @return TableSession
     */
    public function startTableSession(Table $table, $silent = false)
    {
        /**
         * if the table session is disabled
         * there is no need to start a session
         */
        if ( ( bool ) ns()->option->get('ns_gastro_enable_table_sessions', false) === false ) {
            return null;
        }

        $session = $this->getActiveTableSession($table);

        if ($session instanceof TableSession && $silent === false) {
            throw new NotAllowedException(__m('The table session has already be opened.', 'NsGastro'));
        }

        if (! $session instanceof TableSession) {
            $session = new TableSession;
            $session->table_id = $table->id;
            $session->session_starts = ns()->date->getNow()->toDateTimeString();
            $session->save();
        }

        /**
         * if the table session has successfully started
         * we can say that the table is busy from now.
         */
        $table->busy = true;
        $table->save();

        return $session;
    }

    public function getActiveTableSession(Table $table)
    {
        return $table->sessions()->where('active', 1)->orderBy('session_starts', 'desc')->first();
    }

    /**
     * @todo check usage
     */
    public function openTableSession(TableSession $session)
    {
        $session->active = true;
        $session->session_ends = null;
        $session->save();

        return [
            'status'    =>  'success',
            'message'   =>  __('The session has been successfully updated.'),
        ];
    }
}
