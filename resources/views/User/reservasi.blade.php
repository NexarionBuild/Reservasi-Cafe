@extends('layouts.app')

@section('title', 'Reservasi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reservasi.css') }}">
@endpush

@section('content')
    <main class="flex-grow">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="max-w-2xl mx-auto bg-white  dark:bg-surface-dark rounded-lg shadow-lg p-6 sm:p-8 md:p-12">
                <h1 class="text-3xl md:text-4xl font-bold text-center mb-8 text-primary">Reservasi</h1>

                @if (session('gagal'))
                    <div class="mb-4 flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md animate-bounce"
                        role="alert">
                        <p class="font-medium">{{ session('gagal') }}</p>
                    </div>
                @endif
                {{-- {{ dd($keranjang) }} --}}
                @foreach ($pelanggan as $id => $reservasi)
                    <form action="{{ route('form-reservasi') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="nama">Nama</label>

                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="nama" name="nama" placeholder="Nama Lengkap Anda" type="text"
                                    value="{{ $reservasi->nama_pelanggan }}" />

                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="no_hp">No. Hp</label>
                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="no_hp" name="no_hp" placeholder="+62 812-3456-7890" type="tel"
                                    value="{{ $reservasi->no_telepon }}" />
                            </div>

                            {{-- <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="waktu">Waktu</label>
                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="waktu" name="waktu" type="time" min="11:00" max="22:00" />
                            </div> --}}
                            <div x-data="{
                                waktu: '',
                                checkTime() {
                                    if (!this.waktu) return;
                            
                                    // Ambil jam dan menit dari input
                                    const [hours, minutes] = this.waktu.split(':').map(Number);
                            
                                    // Cek jika kurang dari jam 11
                                    if (hours < 11) {
                                        alert('Jam operasional dimulai pukul 11:00');
                                        this.waktu = '11:00';
                                    }
                                    // Cek jika lebih dari jam 22 (atau tepat jam 22 tapi menit lebih dari 00)
                                    else if (hours > 22 || (hours === 22 && minutes > 0)) {
                                        alert('Jam operasional berakhir pukul 22:00');
                                        this.waktu = '22:00';
                                    }
                                }
                            }">
                                <label
                                    class="block text-sm font-medium mb-1 text-text-muted-light dark:text-text-muted-dark"
                                    for="waktu">
                                    Waktu Reservasi (11:00 - 22:00)
                                </label>
                                <input id="waktu" name="waktu" type="time" x-model="waktu" @input="checkTime"
                                    @blur="checkTime" min="11:00" max="22:00"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm" />
                            </div>


                            <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="jumlah_tamu">Jumlah Tamu</label>
                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="jumlah_tamu" min="1" name="jumlah_tamu" placeholder="Jumlah Tamu Maksimal 50"
                                    type="number" />
                            </div>
                            {{-- <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="jumlah_meja">Jumlah Meja</label>
                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="jumlah_meja" min="1" name="jumlah_meja" placeholder="1 Meja Maks 4 Orang"
                                    type="number" />
                            </div> --}}

                            {{-- <div class="md:col-span-2" x-data="{
                                tanggal: '',
                                error: false,
                                today: new Date().toISOString().split('T')[0],
                                checkDate() {
                                    this.error = this.tanggal < this.today;
                                    }
                                }">
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="tanggal">
                                    Tanggal
                                </label>

                                <div class="relative">
                                    <input id="tanggal" name="tanggal" type="date" x-model="tanggal"
                                        @change="checkDate" :min="today"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 
                                        bg-background-light dark:bg-background-dark 
                                        focus:ring-primary focus:border-primary 
                                        transition duration-150 ease-in-out shadow-sm pr-10"
                                        :class="error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : ''" />
                                </div>

                                <!-- Pesan Error -->
                                <p x-show="error" x-transition class="mt-1 text-sm text-red-600">
                                    Tanggal tidak boleh sebelum hari ini
                                </p>
                            </div> --}}
                            <div class="md:col-span-2" x-data="{
                                tanggal: '',
                                error: false,
                                errorMessage: '',
                                today: new Date().toISOString().split('T')[0],
                            
                                // Menghitung tanggal maksimal (hari ini + 7 hari)
                                get maxDate() {
                                    let date = new Date();
                                    date.setDate(date.getDate() + 7);
                                    return date.toISOString().split('T')[0];
                                },
                            
                                checkDate() {
                                    if (this.tanggal < this.today) {
                                        this.error = true;
                                        this.errorMessage = 'Tanggal tidak boleh sebelum hari ini';
                                    } else if (this.tanggal > this.maxDate) {
                                        this.error = true;
                                        this.errorMessage = 'Pemesanan maksimal hanya boleh seminggu ke depan';
                                    } else {
                                        this.error = false;
                                        this.errorMessage = '';
                                    }
                                }
                            }">
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="tanggal">
                                    Tanggal
                                </label>

                                <div class="relative">
                                    <input id="tanggal" name="tanggal" type="date" x-model="tanggal"
                                        @change="checkDate" :min="today" :max="maxDate"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 
                                        bg-background-light dark:bg-background-dark 
                                        focus:ring-primary focus:border-primary 
                                        transition duration-150 ease-in-out shadow-sm pr-10"
                                        :class="error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : ''" />
                                </div>

                                <!-- Pesan Error Dinamis -->
                                <p x-show="error" x-transition x-cloak class="mt-1 text-sm text-red-600">
                                    <span x-text="errorMessage"></span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-8">
                            <button
                                class="w-full bg-primary text-white font-bold py-3 px-4 rounded-md hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-background-dark transition-all duration-300 ease-in-out shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                type="submit">
                                Selanjutnya
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </main>
@endsection
