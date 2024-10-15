<?php
namespace Modules\NsGastro\Tests\Feature;

use Tests\Traits\WithAuthentication;
use Tests\Traits\WithOrderTest;

class GastroCreatePaymentType
{
    use WithOrderTest, WithAuthentication;

    public function test_create_payment_types()
    {
        $this->attemptAuthenticate();
        $this->attemptCreateCustomPaymentType();
    }
}