@extends('layouts.app')

@section('title', 'Detail-Pesanan')

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
                        primary: "#A16207",
                        "background-light": "#FDFBF7",
                        "background-dark": "#1A120B",
                        "card-light": "#FFFFFF",
                        "card-dark": "#2C2319",
                        "text-light": "#333333",
                        "text-dark": "#E5E5E5",
                        "muted-light": "#6B7280",
                        "muted-dark": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#4B5563"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
@endpush

@section('content')
    {{-- {{ dd($reservasi->id_reservasi) }} --}}
    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div
                class="bg-card-light dark:bg-card-dark rounded-lg shadow-lg p-6 sm:p-8 transform transition-transform duration-300 ">
                <h1 class="text-center mb-6 text-2xl font-bold text-primary">Detail Pesanan</h1>
                <div
                    class="flex justify-between items-start mb-6 border-b border-border-light dark:border-border-dark pb-4">
                    <div>
                        {{-- <span class="font-semibold text-muted-light dark:text-muted-dark">Ruangan :
                            
                            {{ count($meja) }}
                        </span> --}}
                        {{-- <ol class="mt-4 list-decimal pl-5">
                            @foreach ($meja as $m)
                                <li class="text-muted-light dark:text-muted-dark mt-1 pl-1"> <span
                                        class="font-light text-muted-light dark:text-muted-dark">{{ strtoupper($m['ruangan']) }}
                                        - {{ $m['kode_meja'] }}</span></li>
                            @endforeach
                        </ol> --}}

                        {{-- <p class="text-muted-light dark:text-muted-dark mt-1">Jumlah Meja: {{ implode(', ', $meja) }}</p> --}}
                    </div>
                    <span class="text-lg font-semibold text-muted-light dark:text-muted-dark">#001</span>
                </div>
                <div
                    class="flex justify-between items-start mb-6 border-b border-border-light dark:border-border-dark pb-4">
                    <div>
                        <span class="font-semibold text-muted-light dark:text-muted-dark">Ruangan :
                            {{ $ruangan }}
                        </span>
                        <br>
                        <span class="font-semibold text-muted-light dark:text-muted-dark">Nomor Meja :
                            {{ $mejaData->kode_meja }}
                        </span>
                        <br>
                        <span class="font-semibold text-muted-light dark:text-muted-dark">Jumlah Kursi :
                            {{ $data['jumlahTamu'] }}
                        </span>
                    </div>
                    {{-- <span class="text-lg font-semibold text-muted-light dark:text-muted-dark">{{ $data['jumlahTamu'] }}</span> --}}
                </div>

                @php
                    $isReservasi = session()->has('menuReservasi');
                @endphp

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between font-semibold text-muted-light dark:text-muted-dark">
                        <span>Pesanan</span>
                        <span>Harga</span>
                    </div>

                    @foreach ($pesanan as $p)
                        <div class="flex justify-between items-center">
                            <span>
                                {{ $isReservasi ? 1 : $p['qty'] }}x
                                <span>{{ $p['nama'] }}</span>
                                <span class="mt-1 block text-xs text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $p['catatan'])) }}
                                </span>
                                {{-- @if (!empty($p['options']))
                                    <span class="mt-1 block text-xs text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $p['options']['temperature'] ?? '')) }},
                                        {{ ucfirst(str_replace('_', ' ', $p['options']['sugar_level'] ?? '')) }},
                                        {{ ucfirst(str_replace('_', ' ', $p['options']['ice_level'] ?? '')) }},
                                    </span>
                                @endif --}}
                            </span>
                            <span>Rp. {{ number_format($p['harga'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-3 pt-4 border-t border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center text-muted-light dark:text-muted-dark">
                        <span>Total Belanja</span>
                        <span>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    {{-- <div class="flex justify-between items-center text-muted-light dark:text-muted-dark">
                        <span>Pajak</span>
                        <span>Rp. {{ number_format($pajak, 0, ',', '.') }}</span>
                    </div> --}}
                    <div
                        class="flex justify-between items-center font-bold text-lg pt-2 text-text-light dark:text-text-dark">
                        <span>Total</span>
                        <span>Rp. {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="mt-8">
                    <h3 class="font-semibold text-muted-light dark:text-muted-dark mb-2">Metode Pembayaran</h3>
                    <div
                        class="flex items-center space-x-2 border border-border-light dark:border-border-dark rounded-md px-3 py-2 w-fit">
                        <span class="icon text-primary">qr_code_2</span>
                        <span class="font-medium">QRIS</span>
                    </div>
                </div>
                <div class="mt-8 flex flex-col items-center space-y-6">
                    <div
                        class="w-48 h-48 sm:w-56 sm:h-56 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/qrcode.png') }}" alt="qrcode">
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold">Batas Upload Bukti Pembayaran</span>
                        <span id="payment-timer" class="font-bold"
                            data-deadline="{{ $paymentDeadlineAt->toIso8601String() }}">
                            {{ $paymentExpired ? 'Waktu habis' : '10:00' }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm">
                        Upload bukti pembayaran maksimal 10 menit setelah halaman ini dibuka.
                    </p>
                </div>

                @if (session('success'))
                    <div class="p-3 mb-4 bg-green-500 text-white rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('gagal'))
                    <div class="p-3 mb-4 bg-red-500 text-white rounded">
                        {{ session('gagal') }}
                    </div>
                @endif

                <form action="{{ route('upload-bukti') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-semibold text-gray-700 mb-5">
                            Upload Bukti Pembayaran
                        </label>

                        <input type="file" name="bukti-pembayaran"accept="image/*"
                            class="w-full border border-gray-300 p-2 rounded bg-white"
                            {{ $paymentExpired ? 'disabled' : '' }}>

                        @error('bukti-pembayaran')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-bold py-3 rounded hover:bg-primary/80 disabled:cursor-not-allowed disabled:opacity-50"
                        {{ $paymentExpired ? 'disabled' : '' }}>
                        Selesaikan Pesanan
                    </button>
                </form>

            </div>
        </div>
    </main>
@endsection

@push('extra-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timerEl = document.getElementById('payment-timer');
            const fileInput = document.querySelector('input[name="bukti-pembayaran"]');
            const submitButton = document.querySelector('button[type="submit"]');

            if (!timerEl) return;

            // KUNCI 10 MENIT DI SINI
            const sepuluhMenit = 10 * 60 * 1000;
            const deadline = Date.now() + sepuluhMenit;

            let intervalId = null;
            let isTriggered = false;

            function setExpiredState() {
                timerEl.textContent = 'Waktu habis';
                if (fileInput) fileInput.disabled = true;
                if (submitButton) submitButton.disabled = true;

                if (isTriggered) return;
                isTriggered = true;

                fetch("{{ route('transaksi.set-gagal') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.href = "{{ route('pesanan.riwayat') }}";
                        }
                    })
                    .catch(error => {
                        console.error('Error saat memperbarui status transaksi:', error);
                    });
            }

            function updateTimer() {
                const now = Date.now();
                const diff = deadline - now;

                if (diff <= 0) {
                    setExpiredState();
                    if (intervalId) clearInterval(intervalId);
                    return;
                }

                // Rumus ini tetap sama, karena fungsinya hanya memformat sisa waktu
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            // Jalankan timer
            updateTimer();
            intervalId = setInterval(updateTimer, 1000);
        });
    </script>
@endpush
