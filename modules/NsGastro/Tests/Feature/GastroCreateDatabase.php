<?php

namespace Modules\NsGastro\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GastroCreateDatabase extends TestCase
{
    use WithFaker;

    public function testCreateDatabaseFile()
    {
        $filePath = base_path('modules/NsGastro/Tests/database.sqlite');

        file_put_contents($filePath, '');

        $this->assertTrue(file_exists($filePath));
    }
}
