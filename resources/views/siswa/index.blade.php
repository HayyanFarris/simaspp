@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Siswa</h1>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('siswa.create') }}"
            class="px-4 py-2 font-bold text-white bg-indigo-600 rounded hover:bg-indigo-700">
            + Tambah Siswa
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
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nominal
                        SPP</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Orang Tua
                    </th>
                    @if(Auth::user()->isAdmin())
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($siswa as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->nis }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $item->nama }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ $item->kelas->nama_kelas ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        Rp {{ number_format($item->nominal_spp, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ $item->nama_orang_tua ?? '-' }}
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="px-4 py-2 text-center border">
                        <!-- Tombol Edit -->
                        <a href="{{ route('siswa.edit', $item) }}"
                            class="inline-flex items-center gap-1 px-3 py-2 mr-1 text-sm font-medium text-white transition duration-150 bg-yellow-500 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('siswa.destroy', $item) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Yakin hapus siswa ini?')">
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
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Belum ada data siswa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection