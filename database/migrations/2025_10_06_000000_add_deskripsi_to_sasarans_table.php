<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeskripsiToSasaransTable extends Migration
{
    public function up()
    {
        Schema::table('sasarans', function (Blueprint $table) {
            $table->string('deskripsi')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('sasarans', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
}
