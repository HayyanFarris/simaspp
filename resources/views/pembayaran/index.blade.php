@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pembayaran SPP</h1>
        @if (Auth::user()->isGuru())
        <a href="{{ route('pembayaran.create') }}"
            class="px-4 py-2 font-bold text-white bg-green-600 rounded hover:bg-green-700">
            + Catat Pembayaran
        </a>
        @endif
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

    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Siswa
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bulan
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tahun
                            Ajaran</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Jumlah</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Dicatat oleh</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Tanggal</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Keterangan</th>
                        @if(Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pembayaran as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            <a href="{{ route('pembayaran.show', $item->siswa_id) }}"
                                class="text-indigo-600 hover:text-indigo-900">
                                {{ $item->siswa->nama ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->siswa->kelas->nama_kelas
                            ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->bulan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->tahun_ajaran }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">Rp {{
                            number_format($item->jumlah_dibayar, 0, ',', '.') }}</td>
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
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->dicatatOleh->name ?? '-'
                            }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{
                            \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->keterangan ?? '-' }}</td>
                        @if(Auth::user()->isAdmin())
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <!-- Tombol Hapus -->
                            <form action="{{ route('pembayaran.destroy', $item) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-white transition duration-150 bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? 12 : 11 }}"
                            class="px-6 py-4 text-center text-gray-500">
                            Belum ada data pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
