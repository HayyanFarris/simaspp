<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas.
     * - Admin: melihat semua kelas
     * - Guru: hanya melihat kelas yang diampunya
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin melihat semua kelas dengan relasi wali kelas
            $kelas = Kelas::with('waliKelas')->get();
        } else {
            // Guru hanya melihat kelas yang diampu
            $kelas = Kelas::where('wali_kelas_id', $user->id)->with('waliKelas')->get();
        }

        return view('kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form tambah kelas (hanya admin)
     */
    public function create()
    {
        // Ambil daftar guru untuk dropdown wali kelas
        $guruList = User::where('role', 'guru')->orderBy('name')->get();
        return view('kelas.create', compact('guruList'));
    }

    /**
     * Menyimpan data kelas baru (hanya admin)
     */
    public function store(StoreKelasRequest $request)
    {
        $validated = $request->validated();

        Kelas::create($validated);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail kelas (opsional, tidak digunakan)
     */
    public function show(Kelas $kelas)
    {
        // Cek otorisasi: guru hanya bisa melihat kelasnya sendiri
        if (Auth::user()->isGuru() && Auth::user()->id !== $kelas->wali_kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        return view('kelas.show', compact('kelas'));
    }

    /**
     * Menampilkan form edit kelas (hanya admin)
     */
    public function edit(Kelas $kelas)
    {
        // Cek otorisasi: hanya admin yang bisa edit
        if (Auth::user()->isGuru()) {
            abort(403, 'Hanya admin yang dapat mengubah data kelas.');
        }

        $guruList = User::where('role', 'guru')->orderBy('name')->get();
        return view('kelas.edit', compact('kelas', 'guruList'));
    }

    /**
     * Update data kelas (hanya admin)
     */
    public function update(UpdateKelasRequest $request, Kelas $kelas)
    {
        if (Auth::user()->isGuru()) {
            abort(403, 'Hanya admin yang dapat mengubah data kelas.');
        }

        $validated = $request->validated();
        $kelas->update($validated);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Hapus data kelas (hanya admin)
     */
    public function destroy(Kelas $kelas)
    {
        if (Auth::user()->isGuru()) {
            abort(403, 'Hanya admin yang dapat menghapus data kelas.');
        }

        // Cek apakah kelas masih memiliki siswa
        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
