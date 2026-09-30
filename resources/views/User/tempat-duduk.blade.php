@extends('layouts.app')

@section('title', 'Tempat-duduk')

@push('styles-cdn')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
@endpush

@push('header-css')
    <style>
        .material-icons {
            font-size: inherit;
            vertical-align: middle;
        }

        body {
            --tw-bg-opacity: 1;
            background-color: rgb(245 243 240 / var(--tw-bg-opacity));
        }

        .dark body {
            --tw-bg-opacity: 1;
            background-color: rgb(44 37 34 / var(--tw-bg-opacity));
        }
    </style>
@endpush


@section('content')

    @if (session('gagal'))
        <div class="mb-4 flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md animate-bounce"
            role="alert">
            <span class="material-icons mr-2">error_outline</span>
            <p class="font-medium">{{ session('gagal') }}</p>
        </div>
    @endif
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="content-main" style="padding: 30px; background: rgb(255, 252, 252); padding-bottom: 50px;">
            <h1 class="text-4xl font-bold text-center text-primary-dark dark:text-secondary mb-12"
                style="padding-top: 20px;">
                Detail Ruangan
            </h1>
            {{-- <div class="meja rounded-lg mb-5" style="border: 1px solid rgb(204, 204, 204);">
                <div class="head-title"
                    style="background-color: rgb(245 243 240 / var(--tw-bg-opacity)); text-align: center; padding: 10px 0;">
                    <h1 class="font-semibold text-primary-dark dark:text-secondary">Silahkan pilih meja yang ingin
                        direservasi</h1>
                </div>
                <div class="title-meja" style="display: flex; justify-content: space-between; padding: 10px 25px;">
                    <p class="font-medium  text-gray-600 font-semibold dark:text-gray-300">Meja yang dipesan :
                        {{ $jumlahMeja }}</p>
                    <p class="font-medium  font-semibold text-gray-600 dark:text-gray-300">Meja terpilih :
                        {{ count($mejaDipilih ?? []) }} / {{ $jumlahMeja }}</p>
                </div>
            </div> --}}

            <form action="{{ route('pilihTempatDuduk') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-center">
                    {{-- <section
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-soft-lg p-6 md:p-8 transform hover:-translate-y-1 transition-transform duration-300"
                        style="border: 1px solid rgb(204, 204, 204);">
                        <div class="grid grid-cols-1 gap-8 items-center">
                            <div class="flex flex-col h-full lg:order-first">
                                <h2 class="text-2xl font-semibold mb-2 text-primary-dark dark:text-secondary">Indoor</h2>
                                <p class="font-medium mb-4 text-gray-600 dark:text-gray-300">Pilih Ruangan</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-8 items-center">
                            <div class="flex items-center space-x-4">
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                    <span class="material-icons text-3xl">chevron_left</span>
                                </button>
                                <div class="bg-secondary/30 dark:bg-primary-dark rounded-lg overflow-hidden">
                                    <img alt="Stylish indoor seating area of the cafe" class="w-full h-75 object-cover"
                                        src="{{ asset('images/ruangan' . 1 . '.jpg') }}" alt="ruangan Indoor" />
                                </div>
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                    <span class="material-icons text-3xl">chevron_right</span>
                                </button>
                            </div>
                            <div class="flex flex-col h-full">
                                <div>
                                    <div class="">
                                        <button id="btn-submit" type="submit" name="ruangan" value="Indoor"
                                            class="w-full bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-dark transition-colors shadow-soft-md hover:shadow-soft-lg transform hover:-translate-y-0.5">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <section
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-soft-lg p-6 md:p-8 transform hover:-translate-y-1 transition-transform duration-300 max-w-xl mx-auto"
                        style="border: 1px solid rgb(204, 204, 204);">
                        <div class="grid grid-cols-1 gap-6 items-center">
                            <div class="flex flex-col h-full">
                                <h2 class="text-2xl font-semibold mb-2 text-primary-dark dark:text-secondary">Indoor</h2>
                                <p class="font-medium mb-4 text-gray-600 dark:text-gray-300">Pilih Ruangan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 items-center">
                            <!-- Area Carousel Gambar -->
                            <div class="flex items-center justify-between space-x-4 w-full">
                                <!-- Tombol Kiri -->
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300 shrink-0">
                                    <span class="material-icons text-3xl">chevron_left</span>
                                </button>

                                <!-- KUNCI PERBAIKAN: Kontainer Gambar Diberi Batas Ukuran Tetap/Responsif -->
                                <div
                                    class="flex-1 w-full max-w-md h-64 md:h-80 bg-secondary/30 dark:bg-primary-dark rounded-lg overflow-hidden">
                                    <img alt="Stylish indoor seating area of the cafe" class="w-full h-full object-cover"
                                        src="{{ asset('images/ruangan' . 1 . '.jpg') }}" />
                                </div>

                                <!-- Tombol Kanan -->
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300 shrink-0">
                                    <span class="material-icons text-3xl">chevron_right</span>
                                </button>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex flex-col w-full mt-4">
                                <button id="btn-submit" type="submit" name="ruangan" value="Indoor"
                                    class="w-full bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-dark transition-colors shadow-soft-md hover:shadow-soft-lg transform hover:-translate-y-0.5">
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                    </section>

                    <section
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-soft-lg p-6 md:p-8 transform hover:-translate-y-1 transition-transform duration-300 max-w-xl mx-auto"
                        style="border: 1px solid rgb(204, 204, 204);">
                        <div class="grid grid-cols-1 gap-6 items-center">
                            <div class="flex flex-col h-full">
                                <h2 class="text-2xl font-semibold mb-2 text-primary-dark dark:text-secondary">Outdoor</h2>
                                <p class="font-medium mb-4 text-gray-600 dark:text-gray-300">Pilih Ruangan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 items-center">
                            <!-- Area Carousel Gambar -->
                            <div class="flex items-center justify-between space-x-4 w-full">
                                <!-- Tombol Kiri -->
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300 shrink-0">
                                    <span class="material-icons text-3xl">chevron_left</span>
                                </button>

                                <!-- KUNCI PERBAIKAN: Kontainer Gambar Diberi Batas Ukuran Tetap/Responsif -->
                                <div
                                    class="flex-1 w-full max-w-md h-64 md:h-80 bg-secondary/30 dark:bg-primary-dark rounded-lg overflow-hidden">
                                    <img alt="Stylish indoor seating area of the cafe" class="w-full h-full object-cover"
                                        src="{{ asset('images/ruangan' . 2 . '.jpg') }}" />
                                </div>

                                <!-- Tombol Kanan -->
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300 shrink-0">
                                    <span class="material-icons text-3xl">chevron_right</span>
                                </button>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex flex-col w-full mt-4">
                                <button id="btn-submit" type="submit" name="ruangan" value="Outdoor"
                                    class="w-full bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-dark transition-colors shadow-soft-md hover:shadow-soft-lg transform hover:-translate-y-0.5">
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                    </section>


                    {{-- <section
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-soft-lg p-6 md:p-8 mb-12 transform hover:-translate-y-1 transition-transform duration-300"
                        style="border: 1px solid rgb(204, 204, 204);">
                        <div class="grid grid-cols-1 gap-8 items-center">
                            <div class="flex flex-col h-full lg:order-first">
                                <h2 class="text-2xl font-semibold mb-2 text-primary-dark dark:text-secondary">Indoor</h2>
                                <p class="font-medium mb-4 text-gray-600 dark:text-gray-300">Pilih Ruangan</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-8 items-center">
                            <div class="flex items-center space-x-4">
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                    <span class="material-icons text-3xl">chevron_left</span>
                                </button>
                                <div class="bg-secondary/30 dark:bg-primary-dark rounded-lg overflow-hidden">
                                    <img alt="Stylish indoor seating area of the cafe" class="w-full h-75 object-cover"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9QDT7m2z8J4jkC8UrlZMThXGON7esBsHMt8N0PdI-4AovNfzbo46pY9U966c-lj9RDYx9ucHQrzk5N8XmC9s2OFIpxiNXbb8mfFnW3aDvA4dyJoVthIItzrC3Aa7MNWy9PKovMtmFF77_C0WBT17v3gZoV5l5GnNE_enXW2_VGFYyMlgR3xLAUjz_Y1c-gGBKxQ9b537KOLZb6AgcPqzJ1yl5Gj732_byo-5Fq5vJRe3SbMj3BQCOWt7S92FnYK1dicnXvqBgAY_7" />
                                </div>
                                <button type="button"
                                    class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                    <span class="material-icons text-3xl">chevron_right</span>
                                </button>
                            </div>

                            <div class="flex flex-col h-full">
                                <div>
                                    <div class="">
                                        <button id="btn-submit" type="submit" name="ruangan" value="Indoor"
                                            class="w-full bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-dark transition-colors shadow-soft-md hover:shadow-soft-lg transform hover:-translate-y-0.5">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>           --}}

                    {{-- <section
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-soft-lg p-6 md:p-8 transform hover:-translate-y-1 transition-transform duration-300"
                        style="border: 1px solid rgb(204, 204, 204);">
                        <div class="grid grid-cols-1 gap-8 items-center">
                            <div class="flex flex-col h-full lg:order-first">
                                <h2 class="text-2xl font-semibold mb-2 text-primary-dark dark:text-secondary">Outdoor</h2>
                                <p class="font-medium mb-4 text-gray-600 dark:text-gray-300">Pilih Ruangan</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 lg:order-last">
                            <button type="button"
                                class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                <span class="material-icons text-3xl">chevron_left</span>
                            </button>
                            <div
                                class="aspect-square flex-grow bg-secondary/30 dark:bg-primary-dark rounded-lg overflow-hidden">
                                <img alt="Sunny outdoor patio seating area of the cafe" class="w-full h-full object-cover"
                                    src="{{ asset('images/ruangan' . 2 . '.jpg') }}" alt="ruangan Outdoor" />
                            </div>
                            <button type="button"
                                class="p-2 rounded-full bg-secondary/50 dark:bg-primary-dark hover:bg-primary hover:text-white transition-all duration-300">
                                <span class="material-icons text-3xl">chevron_right</span>
                            </button>
                        </div>
                        <div class="flex flex-col h-full lg:order-first">
                            <div>
                                <button id="btn-submit" type="submit" name="ruangan" value="Outdoor"
                                    class="w-full bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-dark transition-colors shadow-soft-md hover:shadow-soft-lg transform hover:-translate-y-0.5">
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                    </section> --}}

                </div>

            </form>
        </div>


    </main>

@endsection

@push('extra-scripts')
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const mejaButtons = document.querySelectorAll(".meja-btn");
        //     const inputRuangan = document.getElementById("ruangan");
        //     const inputMeja = document.getElementById("kode_meja");
        //     const btnSubmit = document.getElementById("btn-submit");

        //     mejaButtons.forEach(btn => {
        //         btn.addEventListener("click", function() {

        //             /// hapus selected di semua
        //             mejaButtons.forEach(b => b.classList.remove("selected"));

        //             // set selected ke tombol yang diklik
        //             btn.classList.add("selected");

        //             console.log(inputMeja);

        //             // Isi hidden input
        //             // inputMeja.value = btn.getAttribute("data-value");

        //             // ambil data terpisah
        //             inputRuangan.value = btn.dataset.ruangan;
        //             inputMeja.value = btn.dataset.meja;

        //             // Aktifkan tombol submit
        //             btnSubmit.disabled = false;
        //         });
        //     });
        // });

        // const checkboxes = document.querySelectorAll('input[name="id_meja[]"]');
        // const limit = 4; // Contoh limit

        // checkboxes.forEach(cb => {
        //     cb.addEventListener('change', () => {
        //         const checkedCount = document.querySelectorAll('input[name="id_meja[]"]:checked').length;
        //         if (checkedCount > limit) {
        //             cb.checked = false;
        //             alert("Maksimal pilih " + limit + " meja.");
        //         }
        //     });
        // });
    </script>
@endpush
