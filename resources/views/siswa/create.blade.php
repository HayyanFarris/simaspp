@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Siswa</h1>
        <a href="{{ route('siswa.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
    </div>

    <div class="max-w-2xl p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nis" class="block mb-2 text-sm font-medium text-gray-700">
                    NIS <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nis') border-red-500 @enderror"
                    placeholder="Contoh: 2025001">
                @error('nis')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-red-500 @enderror"
                    placeholder="Nama siswa">
                @error('nama')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kelas_id" class="block mb-2 text-sm font-medium text-gray-700">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="kelas_id" id="kelas_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('kelas_id') border-red-500 @enderror">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" {{ old('kelas_id')==$kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }} ({{ $kelas->waliKelas->name ?? 'Tidak ada wali' }})
                    </option>
                    @endforeach
                </select>
                @error('kelas_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nominal_spp" class="block mb-2 text-sm font-medium text-gray-700">
                    Nominal SPP per Bulan <span class="text-red-500">*</span>
                </label>
                <input type="number" name="nominal_spp" id="nominal_spp" value="{{ old('nominal_spp') }}" step="0.01"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nominal_spp') border-red-500 @enderror"
                    placeholder="Contoh: 150000">
                @error('nominal_spp')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama_orang_tua" class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Orang Tua
                </label>
                <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nama_orang_tua') border-red-500 @enderror"
                    placeholder="Nama orang tua/wali">
                @error('nama_orang_tua')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_telepon_orang_tua" class="block mb-2 text-sm font-medium text-gray-700">
                    No. Telepon Orang Tua
                </label>
                <input type="text" name="no_telepon_orang_tua" id="no_telepon_orang_tua"
                    value="{{ old('no_telepon_orang_tua') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('no_telepon_orang_tua') border-red-500 @enderror"
                    placeholder="Contoh: 08123456789">
                @error('no_telepon_orang_tua')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 font-bold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection