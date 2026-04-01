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
        Schema::create('bimbingan_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbingan_id')->constrained('bimbingans')->onDelete('cascade');
            $table->text('catatan');
            $table->string('file_path')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->index(['bimbingan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_progresses');
    }
};
