<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIMASPP') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Overlay (untuk mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/50 sm:hidden"
            x-transition.opacity.duration.300>
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 transition-transform duration-300 ease-in-out transform -translate-x-full bg-white shadow-lg sm:translate-x-0 sm:relative sm:flex-shrink-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

            <!-- Logo -->
            <div class="flex items-center h-16 px-4 border-b">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">SIMASPP</a>
                <!-- Tombol close khusus mobile (opsional) -->
                <button @click="sidebarOpen = false" class="ml-auto text-gray-500 sm:hidden hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('kelas.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('kelas.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Kelas
                </a>
                <a href="{{ route('siswa.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('siswa.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Siswa
                </a>
                <a href="{{ route('pembayaran.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('pembayaran.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Pembayaran
                </a>
                <a href="{{ route('rekap.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('rekap.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Rekap
                </a>
            </nav>

            <!-- Logout di kiri bawah -->
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-red-50 hover:text-red-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navbar (Hero) -->
            <header class="flex items-center h-16 px-4 bg-white shadow-sm sm:px-6">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <!-- Tombol Hamburger (Mobile) -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-500 sm:hidden hover:text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 truncate">
                            {{ Auth::user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Guru' }}
                        </h2>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <span class="text-xs sm:text-sm text-gray-600 truncate max-w-[100px] sm:max-w-none">
                            Halo, {{ Auth::user()->name }}
                        </span>
                        <span class="hidden text-sm text-gray-400 sm:inline">|</span>
                        <span class="hidden text-sm text-gray-400 sm:inline">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 overflow-y-auto sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>

</html>