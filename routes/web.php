<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekapController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
        ->name('dashboard.admin')
        ->middleware('role:admin');

    Route::get('/dashboard/guru', [DashboardController::class, 'guruDashboard'])
        ->name('dashboard.guru')
        ->middleware('role:guru');

    // Route Rekap Pembayaran
    Route::middleware(['auth'])->group(function () {
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
        // ===== Export Rekap (PDF) =====
        Route::get('/rekap/export-pdf', [RekapController::class, 'exportPDF'])->name('rekap.export-pdf');
    });
});



// Route default dashboard (opsional, bisa diarahkan ke role masing-masing)
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('dashboard.admin');
    }
    return redirect()->route('dashboard.guru');
})->middleware(['auth'])->name('dashboard');

use App\Http\Controllers\KelasController;

// Route CRUD Kelas
Route::middleware(['auth'])->group(function () {
    // Semua user yang login bisa melihat daftar kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');

    // Hanya admin yang bisa create, edit, delete
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });
});

use App\Http\Controllers\SiswaController;

// Route CRUD Siswa
Route::middleware(['auth'])->group(function () {
    // Semua user yang login bisa melihat daftar siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');

    // Hanya admin yang bisa create, edit, delete
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });
});



// Route Pembayaran SPP
Route::middleware(['auth'])->group(function () {
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');

    // Hanya guru yang bisa mencatat pembayaran
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    });

    // Riwayat pembayaran per siswa
    Route::get('/pembayaran/siswa/{siswa}', [PembayaranController::class, 'show'])->name('pembayaran.show');

    // HANYA ADMIN yang bisa hapus pembayaran
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    });
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

require __DIR__ . '/auth.php';
