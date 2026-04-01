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
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_ujian', ['proposal', 'seminar_hasil', 'laporan_skripsi']);
            $table->timestamp('tanggal_ujian');
            $table->string('tempat_ujian');
            $table->foreignId('dosen_pembimbing1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_pembimbing2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dosen_penguji1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_penguji2_id')->nullable()->constrained('users')->onDelete('set null');
            // Approval status per dosen
            $table->enum('status_pembimbing1', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->enum('status_pembimbing2', ['menunggu', 'disetujui', 'ditolak', 'tidak_ada'])->default('tidak_ada');
            $table->enum('status_penguji1', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->enum('status_penguji2', ['menunggu', 'disetujui', 'ditolak', 'tidak_ada'])->default('tidak_ada');
            $table->enum('status_kaprodi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            // Overall status
            $table->enum('status', ['menunggu', 'disetujui_dosen', 'disetujui_kaprodi', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('catatan_kaprodi')->nullable();
            $table->timestamp('approved_kaprodi_at')->nullable();
            $table->timestamps();
            $table->index(['mahasiswa_id', 'jenis_ujian', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
