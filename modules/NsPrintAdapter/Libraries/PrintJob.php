<?php
namespace Modules\NsPrintAdapter\Libraries;

use Modules\NsPrintAdapter\Models\Printer;

class PrintJob 
{
    public function __construct(
        public Printer $printer,
        public string $content,
    )
    {
        // ...
    }
}