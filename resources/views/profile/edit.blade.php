@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-3xl font-bold text-gray-800">Profil Saya</h1>

    <!-- Card Profil -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
        <!-- Info Profil -->
        <div class="lg:col-span-1">
            <div class="p-6 text-center bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-indigo-100 rounded-full">
                    <span class="text-3xl font-bold text-indigo-600">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                <p class="mt-2">
                    @if(Auth::user()->isAdmin())
                    <span class="px-3 py-1 text-xs font-semibold text-white bg-purple-600 rounded-full">Admin</span>
                    @else
                    <span class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">Guru</span>
                    @endif
                </p>
                @if(Auth::user()->isGuru() && Auth::user()->kelasDiampu)
                <p class="mt-3 text-sm text-gray-600">
                    Wali Kelas: <strong>{{ Auth::user()->kelasDiampu->nama_kelas }}</strong>
                </p>
                @endif
            </div>
        </div>

        <!-- Statistik -->
        <div class="lg:col-span-2">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">📊 Ringkasan</h3>
                @if(Auth::user()->isAdmin())
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="p-4 text-center rounded-lg bg-blue-50">
                        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Kelas::count() }}</p>
                        <p class="text-sm text-gray-600">Total Kelas</p>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-green-50">
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Siswa::count() }}</p>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-purple-50">
                        <p class="text-2xl font-bold text-purple-600">{{ \App\Models\PembayaranSpp::count() }}</p>
                        <p class="text-sm text-gray-600">Total Pembayaran</p>
                    </div>
                </div>
                @else
                @php
                $kelas = Auth::user()->kelasDiampu;
                $totalSiswa = $kelas ? $kelas->siswa->count() : 0;
                $totalPembayaran = Auth::user()->pembayaranDicatat()->count();
                @endphp
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="p-4 text-center rounded-lg bg-blue-50">
                        <p class="text-2xl font-bold text-blue-600">{{ $kelas ? $kelas->nama_kelas : '-' }}</p>
                        <p class="text-sm text-gray-600">Kelas Diampu</p>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-green-50">
                        <p class="text-2xl font-bold text-green-600">{{ $totalSiswa }}</p>
                        <p class="text-sm text-gray-600">Jumlah Siswa</p>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-purple-50">
                        <p class="text-2xl font-bold text-purple-600">{{ $totalPembayaran }}</p>
                        <p class="text-sm text-gray-600">Pembayaran Dicatat</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Profil & Password -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Edit Profil -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">✏️ Edit Profil</h3>
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Ubah Password -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">🔒 Ubah Password</h3>
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Hapus Akun (Opsional) -->
    @if(Auth::user()->isAdmin())
    <div class="mt-6">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-4 text-lg font-semibold text-red-600">⚠️ Hapus Akun</h3>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
    @endif
</div>
@endsection