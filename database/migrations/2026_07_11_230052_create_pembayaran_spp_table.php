<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('bulan', 20);
            $table->string('tahun_ajaran', 9);
            $table->decimal('jumlah_dibayar', 10, 2);
            $table->date('tanggal_bayar');
            $table->enum('status', ['lunas', 'belum_lunas', 'cicil'])->default('belum_lunas');
            $table->foreignId('dicatat_oleh')->constrained('users')->onDelete('restrict');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
