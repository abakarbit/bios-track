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
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_bimbingan', ['proposal', 'seminar_hasil', 'laporan_skripsi']);
            $table->date('tanggal_bimbingan');
            $table->string('topik')->nullable();
            $table->text('catatan_mahasiswa')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_kaprodi')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
            $table->index(['mahasiswa_id', 'jenis_bimbingan', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
