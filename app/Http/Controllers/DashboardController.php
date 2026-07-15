<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $user = Auth::user();

        // Statistik utama
        $totalKelas = Kelas::count();
        $totalSiswa = Siswa::count();
        $totalPembayaran = PembayaranSpp::count();
        $totalNominal = PembayaranSpp::sum('jumlah_dibayar');

        // Ambil daftar bulan & tahun ajaran yang tersedia dari database
        $availableBulans = PembayaranSpp::select('bulan')->distinct()->pluck('bulan')->toArray();
        $availableTahun = PembayaranSpp::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();

        // Default: ambil bulan pertama dari data yang ada, atau 'Januari' jika kosong
        $defaultBulan = !empty($availableBulans) ? $availableBulans[0] : 'Januari';
        $defaultTahun = !empty($availableTahun) ? $availableTahun[0] : '2025/2026';

        // Filter dari request, fallback ke default
        $bulanRekap = $request->get('bulan_rekap', $defaultBulan);
        $tahunAjaranRekap = $request->get('tahun_ajaran_rekap', $defaultTahun);

        // Mapping bulan dari Inggris ke Indonesia (jika filter menggunakan Inggris)
        // Tapi karena kita ambil default dari database yang sudah Indonesia, tidak perlu mapping.
        // Jika user pilih dari dropdown yang kita buat (nama Indonesia), langsung pakai.
        // Namun dropdown kita menggunakan kunci January, February, dst, jadi perlu mapping.
        $bulanMapping = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        // Jika bulanRekap adalah kunci Inggris (dari dropdown), mapping ke Indonesia
        $bulanIndonesia = $bulanMapping[$bulanRekap] ?? $bulanRekap;

        // Query rekap
        $totalLunas = PembayaranSpp::where('bulan', $bulanIndonesia)
            ->where('tahun_ajaran', $tahunAjaranRekap)
            ->where('status', 'lunas')->count();
        $totalCicil = PembayaranSpp::where('bulan', $bulanIndonesia)
            ->where('tahun_ajaran', $tahunAjaranRekap)
            ->where('status', 'cicil')->count();
        $totalBelumLunas = PembayaranSpp::where('bulan', $bulanIndonesia)
            ->where('tahun_ajaran', $tahunAjaranRekap)
            ->where('status', 'belum_lunas')->count();

        // Daftar bulan dan tahun untuk dropdown (tetap gunakan kunci Inggris)
        $bulanList = $bulanMapping;
        $tahunAjaranList = PembayaranSpp::distinct()->pluck('tahun_ajaran')->toArray();
        if (empty($tahunAjaranList)) {
            $tahunAjaranList = ['2025/2026'];
        }

        return view('dashboard.admin', compact(
            'totalKelas',
            'totalSiswa',
            'totalPembayaran',
            'totalNominal',
            'totalLunas',
            'totalCicil',
            'totalBelumLunas',
            'bulanRekap',
            'tahunAjaranRekap',
            'bulanList',
            'tahunAjaranList'
        ));
    }

    public function guruDashboard(Request $request)
    {
        $user = Auth::user();
        $kelasDiampu = $user->kelasDiampu;

        // Statistik guru
        $totalSiswa = $kelasDiampu ? $kelasDiampu->siswa->count() : 0;
        $totalPembayaran = PembayaranSpp::where('dicatat_oleh', $user->id)->count();
        $totalNominal = PembayaranSpp::where('dicatat_oleh', $user->id)->sum('jumlah_dibayar');

        // Ambil daftar bulan & tahun yang tersedia
        $availableBulans = PembayaranSpp::select('bulan')->distinct()->pluck('bulan')->toArray();
        $availableTahun = PembayaranSpp::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran')->toArray();

        $defaultBulan = !empty($availableBulans) ? $availableBulans[0] : 'Januari';
        $defaultTahun = !empty($availableTahun) ? $availableTahun[0] : '2025/2026';

        $bulanRekap = $request->get('bulan_rekap', $defaultBulan);
        $tahunAjaranRekap = $request->get('tahun_ajaran_rekap', $defaultTahun);

        $bulanMapping = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $bulanIndonesia = $bulanMapping[$bulanRekap] ?? $bulanRekap;

        $totalLunas = 0;
        $totalCicil = 0;
        $totalBelumLunas = 0;

        if ($kelasDiampu) {
            $siswaIds = $kelasDiampu->siswa->pluck('id')->toArray();
            $totalLunas = PembayaranSpp::whereIn('siswa_id', $siswaIds)
                ->where('bulan', $bulanIndonesia)
                ->where('tahun_ajaran', $tahunAjaranRekap)
                ->where('status', 'lunas')->count();
            $totalCicil = PembayaranSpp::whereIn('siswa_id', $siswaIds)
                ->where('bulan', $bulanIndonesia)
                ->where('tahun_ajaran', $tahunAjaranRekap)
                ->where('status', 'cicil')->count();
            $totalBelumLunas = PembayaranSpp::whereIn('siswa_id', $siswaIds)
                ->where('bulan', $bulanIndonesia)
                ->where('tahun_ajaran', $tahunAjaranRekap)
                ->where('status', 'belum_lunas')->count();
        }

        $bulanList = $bulanMapping;
        $tahunAjaranList = PembayaranSpp::distinct()->pluck('tahun_ajaran')->toArray();
        if (empty($tahunAjaranList)) {
            $tahunAjaranList = ['2025/2026'];
        }

        return view('dashboard.guru', compact(
            'kelasDiampu',
            'totalSiswa',
            'totalPembayaran',
            'totalNominal',
            'totalLunas',
            'totalCicil',
            'totalBelumLunas',
            'bulanRekap',
            'tahunAjaranRekap',
            'bulanList',
            'tahunAjaranList'
        ));
    }
}
