@extends('layouts.app')

{{-- @yield('title', 'Riwayat Transaksi') --}}

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl min-h-screen" x-data="{ activeTab: 'semua' }">

        <!-- HEADER HALAMAN -->
        <div class="mb-6 border-b border-gray-100 dark:border-zinc-800 pb-5">
            <h1 class="text-3xl font-bold text-gray-950 dark:text-white tracking-tight">Daftar Riwayat Transaksi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau semua status pemesanan menu dan reservasi tempat
                Anda secara berkala.</p>
        </div>

        <!-- FILTER TAB -->
        <div class="flex border-b border-gray-200 dark:border-zinc-800 mb-6 gap-2 overflow-x-auto no-scrollbar">
            <button @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-semibold' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                class="whitespace-nowrap pb-3 px-4 border-b-2 text-sm transition-all focus:outline-none">
                Semua Riwayat
            </button>
            <button @click="activeTab = 'aktif'"
                :class="activeTab === 'aktif' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-semibold' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                class="whitespace-nowrap pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-1.5 focus:outline-none">
                Berhasil & Aktif
                <span
                    class="bg-green-50 text-green-700 dark:bg-green-950/50 dark:text-green-400 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ count($pesananSukses) }}</span>
            </button>
            <button @click="activeTab = 'gagal'"
                :class="activeTab === 'gagal' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-semibold' :
                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                class="whitespace-nowrap pb-3 px-4 border-b-2 text-sm transition-all flex items-center gap-1.5 focus:outline-none">
                Gagal & Batal
                <span
                    class="bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-400 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ count($pesananGagal) }}</span>
            </button>
        </div>

        <!-- WRAPPER UTAMA LIST TRANSAKSI -->
        <div class="space-y-6">

            <!-- SECTION 1: TRANSAKSI AKTIF / BERHASIL -->
            <div x-show="activeTab === 'semua' || activeTab === 'aktif'">
                <h2
                    class="text-xs font-bold mb-4 flex items-center gap-2 text-gray-400 dark:text-zinc-400 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                    Transaksi Aktif & Selesai
                </h2>

                <div class="space-y-4">
                    @forelse($pesananSukses as $transaksi)
                        <div
                            class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800/80 rounded-2xl p-4 md:p-5 shadow-sm">

                            <!-- Header Card Nota -->
                            <div
                                class="flex flex-wrap justify-between items-center border-b border-gray-100 dark:border-zinc-800 pb-3 mb-3 gap-3">
                                <div class="flex items-center gap-2.5">
                                    @if (strtolower($transaksi->status) == 'menunggu')
                                        <span
                                            class="text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 px-2.5 py-1 rounded-full uppercase tracking-wider border border-amber-200/40 dark:border-amber-900/30">Pending</span>
                                    @elseif(strtolower($transaksi->status) == 'selesai' || strtolower($transaksi->status) == 'lunas')
                                        <span
                                            class="text-[10px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-200/40 dark:border-green-900/30">Selesai</span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 px-2.5 py-1 rounded-full uppercase tracking-wider border border-blue-200/40 dark:border-blue-900/30">
                                            {{-- {{ $transaksi->status }} --}} DP
                                        </span>
                                    @endif
                                    <span
                                        class="text-xs text-gray-400 dark:text-zinc-500 font-mono tracking-tight">ID-#{{ $transaksi->id_transaksi }}</span>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-zinc-400 font-medium">
                                    {{ $transaksi->created_at ? date('d M Y, H:i', strtotime($transaksi->created_at)) . ' WIB' : '-' }}
                                </span>
                            </div>

                            <!-- List Menu -->
                            <div
                                class="max-h-[220px] overflow-y-auto pr-1 space-y-3 my-3 divide-y divide-gray-50 dark:divide-zinc-800/30 scrollbar-thin">
                                @foreach ($transaksi->items as $item)
                                    <div class="flex items-center justify-between gap-4 pt-2 first:pt-0">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-gray-50 dark:bg-zinc-800 rounded-lg flex items-center justify-center flex-shrink-0 text-gray-400 dark:text-zinc-500 border border-gray-100 dark:border-zinc-700/50">
                                                <span class="material-symbols-outlined text-base">
                                                    {{ Str::contains(strtolower($item->kategori ?? ''), 'minum') ? 'coffee' : 'bakery_dining' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span
                                                    class="font-semibold text-sm text-gray-900 dark:text-zinc-100 block line-clamp-1">{{ $item->nama_menu }}</span>
                                                <span
                                                    class="text-xs text-gray-400 dark:text-zinc-400 block mt-0.5">{{ $item->catatan }}</span></span>
                                                <span
                                                    class="text-xs text-gray-400 dark:text-zinc-400 block mt-0.5">{{ $item->qty }}x
                                                    &bull; <span class="font-mono">Rp
                                                        {{ number_format($item->harga, 0, ',', '.') }}</span></span>
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-800 dark:text-zinc-200 font-mono flex-shrink-0">
                                            Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer Card Nota (Struktur Diperbaiki Agar Tombol Tidak Tertumpu) -->
                            <div
                                class="border-t border-gray-100 dark:border-zinc-800 mt-3 pt-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 text-sm">

                                <!-- Bagian Kiri: Tombol & Metode -->
                                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto relative z-10">
                                    <div
                                        class="text-[11px] text-gray-400 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-800/60 px-2.5 py-1.5 rounded-md border border-gray-100 dark:border-zinc-800">
                                        Metode: <span
                                            class="font-bold text-gray-700 dark:text-zinc-300">{{ strtoupper($transaksi->metode_pembayaran) }}</span>
                                    </div>

                                    @if (strtolower($transaksi->status) != 'menunggu')
                                        <!-- Dioptimasi ukuran sentuhnya untuk HP (py-2 di mobile, py-1 di desktop) -->
                                        <a href="{{ route('pesanan.download', $transaksi->id_transaksi) }}"
                                            class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-800 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:hover:bg-amber-950/50 px-3 py-2 sm:py-1 rounded-md transition-colors border border-amber-200/40 dark:border-amber-900/30 shadow-sm relative z-20">
                                            <span class="material-symbols-outlined text-xs font-bold">download</span>
                                            Unduh PDF
                                        </a>
                                    @else
                                        <a href="{{ $transaksi->checkout_url }}"
                                            class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-800 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:hover:bg-amber-950/50 px-3 py-2 sm:py-1 rounded-md transition-colors border border-amber-200/40 dark:border-amber-900/30 shadow-sm relative z-20">
                                            {{-- <span class="material-symbols-outlined text-xs font-bold">download</span> --}}
                                            Lanjutkan Pembayaran
                                        </a>
                                    @endif
                                </div>

                                <!-- Bagian Kanan: Total Harga (Diberi pembatas yang jelas pada mobile) -->
                                <div
                                    class="flex flex-row sm:flex-col justify-between items-center sm:items-end border-t sm:border-t-0 border-gray-100 dark:border-zinc-800/50 pt-3 sm:pt-0 w-full sm:w-auto">
                                    <span class="text-xs text-gray-400 dark:text-zinc-500 font-medium sm:mb-0.5">Total
                                        Bayar:</span>
                                    <span
                                        class="font-black text-amber-700 dark:text-amber-500 text-base md:text-lg font-mono">
                                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div
                            class="text-center py-12 text-gray-400 dark:text-zinc-500 border border-dashed border-gray-200 dark:border-zinc-800 rounded-2xl text-sm bg-gray-50/30 dark:bg-zinc-900/10">
                            Belum ada riwayat transaksi aktif atau selesai.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SECTION 2: TRANSAKSI GAGAL / BATAL -->
            <div x-show="activeTab === 'semua' || activeTab === 'gagal'">
                <h2
                    class="text-xs font-bold mb-4 flex items-center gap-2 text-gray-400 dark:text-zinc-400 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-red-500 text-lg">cancel</span>
                    Transaksi Gagal & Batal
                </h2>

                <div class="space-y-3">
                    @forelse($pesananGagal as $gagal)
                        <div
                            class="bg-gray-50/40 dark:bg-zinc-900/20 border border-gray-100 dark:border-zinc-800/60 rounded-xl p-4 opacity-80 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-3">
                            <div class="space-y-1 w-full sm:w-auto">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        class="text-[10px] font-bold bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 px-2 py-0.5 rounded-full uppercase border border-red-200/30">
                                        {{ $gagal->status }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-400 dark:text-zinc-500 font-mono">#{{ $gagal->id_transaksi }}</span>
                                    <span class="text-[11px] text-gray-400 dark:text-zinc-500 sm:hidden">&bull;
                                        {{ $gagal->created_at ? date('d M Y', strtotime($gagal->created_at)) : '-' }}</span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-zinc-400 font-medium line-clamp-1">
                                    <span class="text-gray-400 dark:text-zinc-500">Menu:</span>
                                    @foreach ($gagal->items as $itemGagal)
                                        {{ $itemGagal->nama_menu }} <span
                                            class="text-gray-400 font-mono">(x{{ $itemGagal->qty }})</span>{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>

                            <div
                                class="w-full sm:w-auto text-left sm:text-right flex flex-row sm:flex-col justify-between items-center sm:items-end border-t sm:border-t-0 border-gray-100 dark:border-zinc-800/40 pt-2 sm:pt-0 flex-shrink-0">
                                <span
                                    class="text-xs text-gray-400 dark:text-zinc-500 hidden sm:block font-medium">{{ $gagal->created_at ? date('d M Y', strtotime($gagal->created_at)) : '-' }}</span>
                                <span
                                    class="font-bold text-gray-400 dark:text-zinc-500 line-through text-sm font-mono sm:mt-0.5">
                                    Rp {{ number_format($gagal->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-8 text-gray-400 dark:text-zinc-500 border border-dashed border-gray-200 dark:border-zinc-800 rounded-2xl text-sm bg-gray-50/30 dark:bg-zinc-900/10">
                            Tidak ada catatan transaksi gagal.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
@endsection
