@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Catat Pembayaran SPP</h1>
        <a href="{{ route('pembayaran.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
    </div>

    <div class="max-w-2xl p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="siswa_id" class="block mb-2 text-sm font-medium text-gray-700">
                    Pilih Siswa <span class="text-red-500">*</span>
                </label>
                <select name="siswa_id"
                        id="siswa_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('siswa_id') border-red-500 @enderror">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswaList as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                            {{ $siswa->nama }} (NIS: {{ $siswa->nis }}) - Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bulan" class="block mb-2 text-sm font-medium text-gray-700">
                    Bulan <span class="text-red-500">*</span>
                </label>
                <select name="bulan"
                        id="bulan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('bulan') border-red-500 @enderror">
                    <option value="">Pilih Bulan</option>
                    @php
                        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @foreach($bulanList as $bulan)
                        <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>
                            {{ $bulan }}
                        </option>
                    @endforeach
                </select>
                @error('bulan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tahun_ajaran" class="block mb-2 text-sm font-medium text-gray-700">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="tahun_ajaran"
                       id="tahun_ajaran"
                       value="{{ old('tahun_ajaran', '2025/2026') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tahun_ajaran') border-red-500 @enderror"
                       placeholder="Contoh: 2025/2026">
                @error('tahun_ajaran')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah_dibayar" class="block mb-2 text-sm font-medium text-gray-700">
                    Jumlah Dibayar (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="jumlah_dibayar"
                       id="jumlah_dibayar"
                       value="{{ old('jumlah_dibayar') }}"
                       step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('jumlah_dibayar') border-red-500 @enderror"
                       placeholder="Contoh: 150000">
                @error('jumlah_dibayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_bayar" class="block mb-2 text-sm font-medium text-gray-700">
                    Tanggal Bayar <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="tanggal_bayar"
                       id="tanggal_bayar"
                       value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_bayar') border-red-500 @enderror">
                @error('tanggal_bayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-700">
                    Status Pembayaran <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                    <option value="">Pilih Status</option>
                    <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="cicil" {{ old('status') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                    <option value="belum_lunas" {{ old('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-700">
                    Keterangan
                </label>
                <textarea name="keterangan"
                          id="keterangan"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('keterangan') border-red-500 @enderror"
                          placeholder="Catatan tambahan (misal: rincian cicilan)">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 font-bold text-white bg-green-600 rounded hover:bg-green-700">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
