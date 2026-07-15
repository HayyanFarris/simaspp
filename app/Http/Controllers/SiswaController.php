<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa.
     * - Admin: melihat semua siswa
     * - Guru: hanya melihat siswa di kelas yang diampunya
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin melihat semua siswa dengan relasi kelas
            $siswa = Siswa::with('kelas')->orderBy('nama')->get();
        } else {
            // Guru hanya melihat siswa di kelas yang diampu
            $kelasId = $user->kelas_id;
            $siswa = Siswa::with('kelas')
                ->where('kelas_id', $kelasId)
                ->orderBy('nama')
                ->get();
        }

        return view('siswa.index', compact('siswa'));
    }

    /**
     * Menampilkan form tambah siswa (hanya admin)
     */
    public function create()
    {
        // Ambil daftar kelas untuk dropdown
        $user = Auth::user();

        if ($user->isAdmin()) {
            $kelasList = Kelas::with('waliKelas')->orderBy('nama_kelas')->get();
        } else {
            // Guru hanya bisa menambahkan siswa ke kelasnya sendiri (tapi di route sudah dibatasi)
            $kelasList = Kelas::where('id', $user->kelas_id)->with('waliKelas')->get();
        }

        return view('siswa.create', compact('kelasList'));
    }

    /**
     * Menyimpan data siswa baru (hanya admin)
     */
    public function store(StoreSiswaRequest $request)
    {
        $validated = $request->validated();

        // Cek otorisasi tambahan: jika guru, pastikan kelas yang dipilih adalah kelasnya
        $user = Auth::user();
        if ($user->isGuru()) {
            $kelas = Kelas::find($validated['kelas_id']);
            if (!$kelas || $kelas->wali_kelas_id !== $user->id) {
                abort(403, 'Anda tidak dapat menambahkan siswa ke kelas lain.');
            }
        }

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail siswa (opsional)
     */
    public function show(Siswa $siswa)
    {
        // Otorisasi: guru hanya bisa melihat siswa di kelasnya
        $user = Auth::user();
        if ($user->isGuru() && $siswa->kelas_id !== $user->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }

        return view('siswa.show', compact('siswa'));
    }

    /**
     * Menampilkan form edit siswa (hanya admin)
     */
    public function edit(Siswa $siswa)
    {
        $user = Auth::user();

        // Otorisasi: hanya admin yang boleh edit
        if ($user->isGuru()) {
            abort(403, 'Hanya admin yang dapat mengubah data siswa.');
        }

        $kelasList = Kelas::with('waliKelas')->orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    /**
     * Update data siswa (hanya admin)
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        $user = Auth::user();

        if ($user->isGuru()) {
            abort(403, 'Hanya admin yang dapat mengubah data siswa.');
        }

        $validated = $request->validated();
        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil diperbarui!');
    }

    /**
     * Hapus data siswa (hanya admin)
     */
    public function destroy(Siswa $siswa)
    {
        $user = Auth::user();

        if ($user->isGuru()) {
            abort(403, 'Hanya admin yang dapat menghapus data siswa.');
        }

        // Cek apakah siswa memiliki riwayat pembayaran
        if ($siswa->pembayaran()->count() > 0) {
            return redirect()->route('siswa.index')
                ->with('error', 'Siswa tidak dapat dihapus karena memiliki riwayat pembayaran.');
        }

        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }
}
