<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeChangedByNullableInStatusHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable(false)->change();
        });
    }
}
