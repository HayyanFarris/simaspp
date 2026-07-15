@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    statistikOpen: true,
    rekapOpen: true
}">
    <!-- Section Statistik -->
    <div class="bg-white rounded-lg shadow">
        <div class="flex items-center justify-between px-6 py-3 border-b cursor-pointer"
            @click="statistikOpen = !statistikOpen">
            <h2 class="text-lg font-semibold text-gray-800">📊 Statistik Kelas Anda</h2>
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200"
                :class="{ 'rotate-180': statistikOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div x-show="statistikOpen" x-transition.duration.300ms class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- 4 statistik cards -->
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Kelas Diampu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $kelasDiampu ? $kelasDiampu->nama_kelas : '-' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pembayaran Dicatat</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPembayaran }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 1v1m0 1v1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Nominal</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalNominal, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Rekap Status -->
    <div class="bg-white rounded-lg shadow">
        <div class="flex items-center justify-between px-6 py-3 border-b cursor-pointer"
            @click="rekapOpen = !rekapOpen">
            <h2 class="text-lg font-semibold text-gray-800">📋 Rekap Status Pembayaran Kelas {{ $kelasDiampu ?
                $kelasDiampu->nama_kelas : '-' }}</h2>
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': rekapOpen }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div x-show="rekapOpen" x-transition.duration.300ms class="p-6">
            <!-- Filter otomatis (tanpa tombol) -->
            <div class="flex flex-wrap items-center justify-between mb-4">
                <form method="GET" action="{{ route('dashboard.guru') }}" x-data="{ form: $el }" @change="form.submit()"
                    class="flex flex-wrap items-center gap-3">
                    <div>
                        <label for="bulan_rekap" class="sr-only">Bulan</label>
                        <select name="bulan_rekap" id="bulan_rekap"
                            class="px-3 py-1 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($bulanList as $key => $value)
                            <option value="{{ $key }}" {{ $bulanRekap==$key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_ajaran_rekap" class="sr-only">Tahun Ajaran</label>
                        <select name="tahun_ajaran_rekap" id="tahun_ajaran_rekap"
                            class="px-3 py-1 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaranRekap==$ta ? 'selected' : '' }}>{{ $ta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tombol Filter dihapus -->
                </form>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @if($totalLunas == 0 && $totalCicil == 0 && $totalBelumLunas == 0)
                <div class="col-span-3 p-4 text-center rounded-lg bg-yellow-50">
                    <p class="text-yellow-700">⚠️ Belum ada data pembayaran untuk bulan <strong>{{
                            $bulanList[$bulanRekap] ?? $bulanRekap }}</strong> tahun ajaran <strong>{{ $tahunAjaranRekap
                            }}</strong>.</p>
                    <p class="mt-1 text-sm text-gray-500">Silakan pilih bulan lain atau catat pembayaran baru.</p>
                </div>
                @else
                <div class="p-4 text-center rounded-lg bg-green-50">
                    <p class="text-2xl font-bold text-green-600">{{ $totalLunas }}</p>
                    <p class="text-sm text-gray-600">✅ Lunas</p>
                </div>
                <div class="p-4 text-center rounded-lg bg-yellow-50">
                    <p class="text-2xl font-bold text-yellow-600">{{ $totalCicil }}</p>
                    <p class="text-sm text-gray-600">🔄 Cicil</p>
                </div>
                <div class="p-4 text-center rounded-lg bg-red-50">
                    <p class="text-2xl font-bold text-red-600">{{ $totalBelumLunas }}</p>
                    <p class="text-sm text-gray-600">❌ Belum Lunas</p>
                </div>
                @endif
            </div>
            <div class="mt-4 text-sm text-center text-gray-500">
                Data untuk bulan <strong>{{ $bulanList[$bulanRekap] ?? $bulanRekap }}</strong> tahun ajaran <strong>{{
                    $tahunAjaranRekap }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection
