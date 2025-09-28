<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahuns', function (Blueprint $table) {
            $table->integer('year')->after('id');
            $table->boolean('status')->default(true)->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('tahuns', function (Blueprint $table) {
            $table->dropColumn(['year', 'status']);
        });
    }
};
