<?php

use Modules\NsGastro\Tests\Traits\WithPrinterTest;
use Tests\TestCase;
use Tests\Traits\WithAuthentication;
use Tests\Traits\WithOrderTest;

class GastroSplitPrintTest extends TestCase
{
    use WithOrderTest, WithAuthentication, WithPrinterTest;
    
    public function testSplitPrint()
    {
        $this->attemptAuthenticate();
        $this->attemptSplitPrint();
    }
}