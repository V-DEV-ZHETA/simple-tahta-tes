<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToBangkomsTable extends Migration
{
    public function up()
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->softDeletes(); // Adds deleted_at column
        });
    }

    public function down()
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
