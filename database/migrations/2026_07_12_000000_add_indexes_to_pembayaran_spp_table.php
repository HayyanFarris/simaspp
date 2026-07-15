<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom bulan, tahun_ajaran, dan status sering dipakai bersamaan di WHERE
     * (dashboard, rekap, export PDF) tapi belum punya index -> full table scan.
     * siswa_id sudah otomatis punya index dari foreign key constraint.
     */
    public function up(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->index(['bulan', 'tahun_ajaran', 'status'], 'pembayaran_spp_bulan_tahun_status_idx');
            $table->index(['siswa_id', 'bulan', 'tahun_ajaran'], 'pembayaran_spp_siswa_bulan_tahun_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropIndex('pembayaran_spp_bulan_tahun_status_idx');
            $table->dropIndex('pembayaran_spp_siswa_bulan_tahun_idx');
        });
    }
};
