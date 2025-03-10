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
        // Buat tabel desa lebih dulu
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->float('longitude');
            $table->float('latitude');
            $table->timestamps();
        });

        // Buat tabel kategori lebih dulu
        Schema::create('kategories', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->timestamps();
        });

        // Tabel data dibuat setelah kategori & desa
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->foreignId('kategory_id')->constrained('kategories')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
        Schema::dropIfExists('kategories');  // Sesuai dengan tabel yang dibuat di atas
        Schema::dropIfExists('desa');
    }
};
