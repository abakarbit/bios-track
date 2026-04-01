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
        Schema::table('bimbingan_progresses', function (Blueprint $table) {
            //datetime untuk mencatat kapan progress bimbingan dilakukan, terpisah dari created_at agar bisa diinput manual sesuai tanggal bimbingan sebenarnya
            $table->timestamp('tanggal_bimbingan')->nullable()->after('bimbingan_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan_progresses', function (Blueprint $table) {
            $table->dropColumn('tanggal_bimbingan');
        });
    }
};
