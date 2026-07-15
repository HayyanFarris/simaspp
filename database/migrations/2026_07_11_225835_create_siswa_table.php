<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nama', 255);
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->decimal('nominal_spp', 10, 2);
            $table->string('nama_orang_tua', 255)->nullable();
            $table->string('no_telepon_orang_tua', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
