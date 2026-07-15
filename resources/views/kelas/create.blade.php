@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Kelas</h1>
        <a href="{{ route('kelas.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
    </div>

    <div class="max-w-2xl p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama_kelas" class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nama_kelas') border-red-500 @enderror"
                    placeholder="Contoh: 7A, 8B, 9C">
                @error('nama_kelas')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tingkat" class="block mb-2 text-sm font-medium text-gray-700">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tingkat" id="tingkat" value="{{ old('tingkat') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tingkat') border-red-500 @enderror"
                    placeholder="Contoh: 7, 8, 9">
                @error('tingkat')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="wali_kelas_id" class="block mb-2 text-sm font-medium text-gray-700">
                    Wali Kelas <span class="text-red-500">*</span>
                </label>
                <select name="wali_kelas_id" id="wali_kelas_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('wali_kelas_id') border-red-500 @enderror">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($guruList as $guru)
                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id')==$guru->id ? 'selected' : '' }}>
                        {{ $guru->name }} ({{ $guru->email }})
                    </option>
                    @endforeach
                </select>
                @error('wali_kelas_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                @if($guruList->isEmpty())
                <p class="mt-1 text-sm text-yellow-500">
                    ⚠️ Belum ada guru. Tambahkan guru terlebih dahulu.
                </p>
                @endif
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