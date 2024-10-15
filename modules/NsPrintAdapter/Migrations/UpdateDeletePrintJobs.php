<?php

/**
* Table Migration
* @package 5.0.0
**/

namespace Modules\NsPrintAdapter\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('nexopos_printers_jobs');
    }

    public function down()
    {
        // ...
    }
};