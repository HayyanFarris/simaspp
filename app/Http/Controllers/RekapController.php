<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PembayaranSpp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    /**
     * Ambil data rekap berdasarkan bulan dan tahun ajaran
     */
    private function getRekapData($bulan, $tahunAjaran)
    {
        $user = Auth::user();

        // Mapping bulan Indonesia
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
        $bulanIndonesia = $bulanMapping[$bulan] ?? $bulan;

        // Ambil daftar kelas sesuai role
        if ($user->isAdmin()) {
            $kelasList = Kelas::with(['waliKelas', 'siswa'])->orderBy('nama_kelas')->get();
        } else {
            $kelasList = Kelas::with(['waliKelas', 'siswa'])
                ->where('wali_kelas_id', $user->id)
                ->orderBy('nama_kelas')
                ->get();
        }

        // Siapkan data rekap per kelas
        $rekapData = [];

        foreach ($kelasList as $kelas) {
            $dataKelas = [
                'kelas' => $kelas,
                'siswa' => [],
                'total_lunas' => 0,
                'total_belum_lunas' => 0,
                'total_cicil' => 0,
                'total_siswa' => $kelas->siswa->count(),
            ];

            foreach ($kelas->siswa as $siswa) {
                $pembayaran = PembayaranSpp::where('siswa_id', $siswa->id)
                    ->where('bulan', $bulanIndonesia)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->first();

                $status = $pembayaran ? $pembayaran->status : 'belum_lunas';
                $jumlahDibayar = $pembayaran ? $pembayaran->jumlah_dibayar : 0;

                if ($status == 'lunas') {
                    $dataKelas['total_lunas']++;
                } elseif ($status == 'cicil') {
                    $dataKelas['total_cicil']++;
                } else {
                    $dataKelas['total_belum_lunas']++;
                }

                $dataKelas['siswa'][] = [
                    'siswa' => $siswa,
                    'status' => $status,
                    'jumlah_dibayar' => $jumlahDibayar,
                    'pembayaran' => $pembayaran,
                ];
            }

            $rekapData[] = $dataKelas;
        }

        return [
            'rekapData' => $rekapData,
            'bulanIndonesia' => $bulanIndonesia,
        ];
    }

    /**
     * Menampilkan halaman rekap
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('F'));
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');

        $data = $this->getRekapData($bulan, $tahunAjaran);

        $bulanList = [
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

        $tahunAjaranList = PembayaranSpp::distinct()->pluck('tahun_ajaran')->toArray();
        if (empty($tahunAjaranList)) {
            $tahunAjaranList = ['2025/2026'];
        }

        return view('rekap.index', array_merge($data, [
            'bulan' => $bulan,
            'tahunAjaran' => $tahunAjaran,
            'bulanList' => $bulanList,
            'tahunAjaranList' => $tahunAjaranList,
        ]));
    }

    /**
     * Export rekap ke PDF
     */
    public function exportPDF(Request $request)
    {
        $bulan = $request->get('bulan', date('F'));
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');

        $data = $this->getRekapData($bulan, $tahunAjaran);

        $pdf = Pdf::loadView('rekap.export', [
            'rekapData' => $data['rekapData'],
            'bulanIndonesia' => $data['bulanIndonesia'],
            'tahunAjaran' => $tahunAjaran,
        ]);

        $pdf->setPaper('A4', 'landscape');

        // Sanitasi nama file: ganti '/' dengan '-'
        $safeBulan = str_replace('/', '-', $data['bulanIndonesia']);
        $safeTahun = str_replace('/', '-', $tahunAjaran);
        $filename = "rekap-pembayaran-{$safeBulan}-{$safeTahun}.pdf";

        return $pdf->download($filename);
    }
}
