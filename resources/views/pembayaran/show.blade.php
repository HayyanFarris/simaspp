@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Pembayaran - {{ $siswa->nama }}
        </h1>
        <a href="{{ route('pembayaran.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
    </div>

    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">NIS</p>
                <p class="font-semibold">{{ $siswa->nis }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kelas</p>
                <p class="font-semibold">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Nominal SPP</p>
                <p class="font-semibold">Rp {{ number_format($siswa->nominal_spp, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bulan
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tahun
                        Ajaran</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jumlah
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Dicatat
                        oleh</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($riwayat as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->bulan }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->tahun_ajaran }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status == 'lunas')
                        <span
                            class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Lunas</span>
                        @elseif($item->status == 'cicil')
                        <span
                            class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Cicil</span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Belum
                            Lunas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ $item->dicatatOleh->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item->keterangan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Belum ada data pembayaran untuk siswa ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection