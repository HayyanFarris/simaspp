<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Http\Requests\StorePembayaranRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran (riwayat semua siswa).
     * - Admin: melihat semua pembayaran
     * - Guru: hanya melihat pembayaran siswa di kelasnya
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin melihat semua pembayaran dengan relasi siswa & pencatat
            $pembayaran = PembayaranSpp::with(['siswa', 'dicatatOleh'])
                ->orderBy('tanggal_bayar', 'desc')
                ->get();
        } else {
            // Guru hanya melihat pembayaran siswa di kelas yang diampu
            $kelasId = $user->kelas_id;
            $pembayaran = PembayaranSpp::with(['siswa', 'dicatatOleh'])
                ->whereHas('siswa', function ($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                })
                ->orderBy('tanggal_bayar', 'desc')
                ->get();
        }

        return view('pembayaran.index', compact('pembayaran'));
    }

    /**
     * Menampilkan form input pembayaran (hanya untuk guru)
     * Menampilkan daftar siswa di kelas yang diampu oleh guru tersebut
     */
    public function create()
    {
        $user = Auth::user();

        // Hanya guru yang boleh mengakses form ini
        if ($user->isAdmin()) {
            // Admin tidak boleh mencatat pembayaran (sesuai PRD)
            abort(403, 'Hanya guru yang dapat mencatat pembayaran.');
        }

        // Ambil siswa di kelas yang diampu guru
        $siswaList = Siswa::with('kelas')
            ->where('kelas_id', $user->kelas_id)
            ->orderBy('nama')
            ->get();

        // Jika tidak ada siswa, tampilkan pesan
        if ($siswaList->isEmpty()) {
            return redirect()->route('dashboard.guru')
                ->with('error', 'Kelas Anda belum memiliki siswa.');
        }

        return view('pembayaran.create', compact('siswaList'));
    }

    /**
     * Menyimpan data pembayaran baru
     */
    public function store(StorePembayaranRequest $request)
    {
        $user = Auth::user();

        // Hanya guru yang boleh menyimpan
        if ($user->isAdmin()) {
            abort(403, 'Hanya guru yang dapat mencatat pembayaran.');
        }

        $validated = $request->validated();

        // Pastikan siswa berada di kelas yang diampu guru
        $siswa = Siswa::find($validated['siswa_id']);
        if (!$siswa || $siswa->kelas_id !== $user->kelas_id) {
            abort(403, 'Anda tidak dapat mencatat pembayaran untuk siswa di luar kelas Anda.');
        }

        // Tambahkan dicatat_oleh
        $validated['dicatat_oleh'] = $user->id;

        // Jika status belum_lunas, jumlah_dibayar = 0 (atau sesuai input)
        // Biarkan user mengisi jumlah, tapi validasi sudah memastikan

        PembayaranSpp::create($validated);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    /**
     * Menampilkan riwayat pembayaran per siswa (FR-05)
     */
    public function show(Siswa $siswa)
    {
        $user = Auth::user();

        // Otorisasi: guru hanya bisa melihat siswa di kelasnya
        if ($user->isGuru() && $siswa->kelas_id !== $user->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }

        // Ambil riwayat pembayaran siswa
        $riwayat = PembayaranSpp::with('dicatatOleh')
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        return view('pembayaran.show', compact('siswa', 'riwayat'));
    }

    /**
     * Hapus data pembayaran (hanya admin)
     */
    public function destroy(PembayaranSpp $pembayaran)
    {
        $user = Auth::user();

        // Hanya admin yang boleh menghapus
        if ($user->isGuru()) {
            abort(403, 'Hanya admin yang dapat menghapus data pembayaran.');
        }

        // Hapus data
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Riwayat pembayaran berhasil dihapus!');
    }
}
