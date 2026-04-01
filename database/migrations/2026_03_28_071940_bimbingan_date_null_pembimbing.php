<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->date('tanggal_bimbingan')->nullable()->change();
            $table->integer('pembimbing')->nullable()->after('jenis_bimbingan')->comment('1 untuk pembimbing 1, 2 untuk pembimbing 2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->date('tanggal_bimbingan')->nullable(false)->change();
        });
    }
};
