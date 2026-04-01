<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'admin' to role enum
        DB::statement("ALTER TABLE users MODIFY role ENUM('mahasiswa','dosen','kaprodi','admin') DEFAULT 'mahasiswa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'admin' from role enum
        DB::statement("ALTER TABLE users MODIFY role ENUM('mahasiswa','dosen','kaprodi') DEFAULT 'mahasiswa'");
    }
};
