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
       Schema::create('arsip', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->string('judul');
    $table->string('nomor')->nullable();
    $table->string('tahun')->nullable();

    $table->string('kategori');
    $table->string('kategori_kelompok')->nullable();
    $table->string('jenis_arsip');

    $table->enum('status_akses', ['publik', 'private'])->default('publik');
    $table->string('bagian')->nullable();

    $table->string('lokasi')->nullable();
    $table->text('deskripsi')->nullable();

    $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
