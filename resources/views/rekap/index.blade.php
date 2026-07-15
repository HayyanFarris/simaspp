@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Rekap Pembayaran SPP</h1>
    </div>

    @if(session('success'))
    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filter -->
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <form method="GET" action="{{ route('rekap.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="bulan" class="block mb-1 text-sm font-medium text-gray-700">Bulan</label>
                <select name="bulan" id="bulan"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($bulanList as $key => $value)
                    <option value="{{ $key }}" {{ $bulan==$key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tahun_ajaran" class="block mb-1 text-sm font-medium text-gray-700">Tahun Ajaran</label>
                <select name="tahun_ajaran" id="tahun_ajaran"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" {{ $tahunAjaran==$ta ? 'selected' : '' }}>
                        {{ $ta }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-6 py-2 font-bold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                    Tampilkan
                </button>
            </div>
            <!-- TOMBOL EXPORT PDF -->
            <div class="ml-auto">
                <a href="{{ route('rekap.export-pdf', ['bulan' => $bulan, 'tahun_ajaran' => $tahunAjaran]) }}"
                    class="px-4 py-2 font-bold text-white bg-red-600 rounded hover:bg-red-700">
                    📄 Export PDF
                </a>
            </div>
        </form>
    </div>
    <!-- Rekap per Kelas -->
    @forelse($rekapData as $data)
    <div class="mb-8 overflow-hidden bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    Kelas {{ $data['kelas']->nama_kelas }}
                    <span class="ml-2 text-sm font-normal text-gray-500">
                        (Wali Kelas: {{ $data['kelas']->waliKelas->name ?? '-' }})
                    </span>
                </h2>
                <div class="flex gap-4 text-sm">
                    <span class="text-green-600">✅ Lunas: {{ $data['total_lunas'] }}</span>
                    <span class="text-yellow-600">🔄 Cicil: {{ $data['total_cicil'] }}</span>
                    <span class="text-red-600">❌ Belum Lunas: {{ $data['total_belum_lunas'] }}</span>
                    <span class="text-gray-600">📊 Total: {{ $data['total_siswa'] }}</span>
                </div>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                        Siswa</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jumlah
                        Dibayar</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($data['siswa'] as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item['siswa']->nis }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item['siswa']->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item['status'] == 'lunas')
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅
                            Lunas</span>
                        @elseif($item['status'] == 'cicil')
                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">🔄
                            Cicil</span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">❌ Belum
                            Lunas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        @if($item['jumlah_dibayar'] > 0)
                        Rp {{ number_format($item['jumlah_dibayar'], 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item['pembayaran'] ? $item['pembayaran']->keterangan ?? '-' : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Belum ada siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @empty
    <div class="p-8 text-center bg-white rounded-lg shadow-md">
        <p class="text-gray-500">Belum ada data kelas.</p>
    </div>
    @endforelse
</div>
@endsection