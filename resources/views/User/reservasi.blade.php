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

                     

                            <div x-data="{
                                // Ambil tanggal hari ini format YYYY-MM-DD
                                getTodayDate() {
                                        const d = new Date();
                                        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                                    },
                            
                                    // Ambil tanggal 7 hari ke depan format YYYY-MM-DD
                                    getMaxDate() {
                                        const d = new Date();
                                        d.setDate(d.getDate() + 7); // Tambahkan 7 hari dari hari ini
                                        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                                    },
                            
                                    // State data
                                    tanggal: '',
                                    waktu: '',
                                    listJam: ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'],
                            
                                    // Reset pilihan waktu jika pengguna memilih tanggal yang berbeda
                                    onTanggalChange() {
                                        if (this.waktu && this.isTerlewat(this.waktu)) {
                                            this.waktu = '';
                                        }
                                    },
                            
                                    // Fungsi cek jam terlewat
                                    isTerlewat(jamMenit) {
                                        if (!this.tanggal || this.tanggal !== this.getTodayDate()) {
                                            return false;
                                        }
                            
                                        const sekarang = new Date();
                                        const jamSekarang = sekarang.getHours();
                                        const menitSekarang = sekarang.getMinutes();
                            
                                        const [jamTarget, menitTarget] = jamMenit.split(':').map(Number);
                            
                                        if (jamSekarang > jamTarget) return true;
                                        if (jamSekarang === jamTarget && menitSekarang > menitTarget) return true;
                            
                                        return false;
                                    }
                                }" x-init="tanggal = getTodayDate()">

                                <!-- 1. INPUT TANGGAL -->
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1 text-text-muted-light dark:text-text-muted-dark"
                                        for="tanggal">
                                        Tanggal Reservasi
                                    </label>
                                    <input name="tanggal" id="tanggal" type="date" x-model="tanggal"
                                        @change="onTanggalChange()" :min="getTodayDate()" :max="getMaxDate()"
                                        class="block w-full rounded-md border-gray-300">
                                </div>

                                <!-- 2. PILIHAN JAM RESERVASI -->
                                <div>
                                    <label
                                        class="block text-sm font-medium mb-1 text-text-muted-light dark:text-text-muted-dark"
                                        for="waktu">
                                        Waktu Reservasi (11:00 - 22:00)
                                    </label>

                                    <select id="waktu" name="waktu" x-model="waktu"
                                        class="block w-full rounded-md border-gray-300">
                                        <option value="">Pilih Waktu</option>
                                        <template x-for="jam in listJam" :key="jam">
                                            <option :value="jam" x-text="jam" :disabled="isTerlewat(jam)">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>


                            <div>
                                <label
                                    class="block text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1"
                                    for="jumlah_tamu">Jumlah Tamu</label>
                                <input
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:ring-primary focus:border-primary transition duration-150 ease-in-out shadow-sm"
                                    id="jumlah_tamu" min="1" name="jumlah_tamu" placeholder="Jumlah Tamu Maksimal 10"
                                    type="number" />
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
<script>
    function kunciMenit(input) {
        if (input.value) {
            // Mengambil 2 digit jam pertama, lalu memaksa menitnya menjadi :00
            let jam = input.value.split(':')[0];
            input.value = jam + ':00';
        }
    }
</script>
