@extends('layouts.app')

@section('title', 'Detail-Transaksi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detail-transaksi.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
@endpush

@push('header-script')
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#A35709", // Warm brown for a coffee shop theme
                        "background-light": "#F0E9D2", // Creamy light background
                        "background-dark": "#1B1A17", // Dark coffee bean color
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                    },
                },
            },
        };
    </script>
@endpush

@section('content')
    <main class="flex-grow py-12 sm:py-16 lg:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="max-w-md mx-auto bg-white dark:bg-[#2a2824] shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-6 sm:p-8">

                <div class="text-center border-b border-dashed border-gray-300 dark:border-gray-600 pb-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Detail Transaksi</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">#{{ $reservasi->id_reservasi }}</p>
                </div>

                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300 ">

                    <div class="flex justify-between">
                        <span>Nama</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $data['nama'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>No Hp</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $data['noHp'] }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Jumlah Tamu</span>
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $data['jumlahTamu'] }}</span>
                    </div>

                    <div class="border-t border-b border-dashed border-gray-300 dark:border-gray-600 py-4 mb-4">
                        <div class="flex justify-between">
                            <h2 class="font-semibold text-gray-600 dark:text-gray-300">Ruangan</h2>
                            <h2 class="font-semibold text-gray-600 dark:text-gray-300">{{ $ruangan }}</h2>
                        </div>
                        <div class="flex justify-between">
                            <h2 class="font-semibold text-gray-600 dark:text-gray-300">Nomor Meja</h2>
                            <h2 class="font-semibold text-gray-600 dark:text-gray-300">{{ $dataMeja->kode_meja }}</h2>
                        </div>

                        <div class="flex justify-between mt-1">
                            <ol class="list-decimal pl-3">
                                {{-- @foreach ($meja as $m)
                                    <li class="text-muted-light dark:text-muted-dark mt-1"><span class="font-light text-muted-light dark:text-muted-dark pl-1">{{ strtoupper($m['ruangan']) }} - {{ $m['kode_meja'] }}</span></li>
                                @endforeach --}}
                            </ol>
                        </div>
                    </div>

                </div>

                <div class="border-b border-dashed border-gray-300 dark:border-gray-600 py-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <h2 class="font-semibold text-gray-600 dark:text-gray-300">Pesanan</h2>
                        <h2 class="font-semibold text-gray-600 dark:text-gray-300">Harga</h2>
                    </div>
                    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        @foreach ($pesanan as $p)
                            <div class="flex justify-between">
                                <span>
                                    {{ $p['qty'] }}x {{ $p['nama'] }}
                                    <span class="mt-1 block text-xs text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $p['catatan'])) }}
                                    </span>
                                    {{-- @if (!empty($p['options']))
                                        <span class="mt-1 block text-xs text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $p['options']['temperature'] ?? '')) }},
                                            {{ ucfirst(str_replace('_', ' ', $p['options']['sugar_level'] ?? '')) }},
                                            {{ ucfirst(str_replace('_', ' ', $p['options']['ice_level'] ?? '')) }}
                                        </span>
                                    @endif --}}
                                </span>
                                <span>Rp. {{ number_format($p['harga'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-3 text-sm mb-6">
                    {{-- <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Total Harga</span>
                        <span>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div> --}}
                    {{-- <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Pajak</span>
                        <span>Rp. {{ number_format($pajak, 0, ',', '.') }}</span>
                    </div> --}}
                    <div
                        class="flex justify-between text-base font-bold text-gray-800 dark:text-gray-100 mt-3 pt-3  border-gray-200 dark:border-gray-700">
                        <span>Total</span>
                        <span>Rp. {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- <a href="{{ route('transaksi.download', ['id' => $reservasi->id_reservasi]) }}" target="_blank"
                   class="w-full block text-center bg-primary text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-all duration-300 transform hover:scale-105 active:scale-100"> --}}

                <div
                    class="flex items-center gap-3 bg-amber-50 text-amber-700 border-amber-200 text-yellow-800 px-4 py-2 rounded">
                    <svg class="w-20 h-20 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M5.07 19h13.86L12 5 5.07 19z" />
                    </svg>
                    <div class="text-[12px]">
                        <span>Silahkan download bukti transaksi dan tunggu admin menghubungi melalui WA. atau hubungi admin
                            langsung</span>
                        <a class="text-blue-600 hover:underline"href="https://wa.me/087815349226">087815349226</a>
                    </div>
                </div>

                <button
                    class="w-full bg-primary text-white font-semibold mt-5 py-3 px-4 rounded-lg shadow-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-all duration-300 transform hover: active:scale-100">
                    <a href="{{ route('transaksi.download', ['id' => $reservasi->id_reservasi]) }}">Download Transaksi</a>
                </button>

                <form action="{{ route('reset.session') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-primary text-white font-semibold mt-5 py-3 px-4 rounded-lg shadow-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-all duration-300 transform hover: active:scale-100">
                        Kembali
                    </button>
                </form>

                </a>
            </div>
        </div>
    </main>
@endsection
