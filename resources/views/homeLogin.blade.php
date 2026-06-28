@extends('layouts.app')

@section('title', 'HomePelanggan')

@section('content')
    @php
        $hubungi = [
            'telepon1' => '+62-812-1234-1234',
            'Email' => 'hihoodeastside@gmail.com',
            'alamat' => 'Jl. Nasional 1 Patrol, Kec. Patrol, Kab. Indramayu, Jawa Barat 45257',
        ];
        $contactPhone = '6287815349226';
        $kopiSenjaText = file_exists(resource_path('kopi_senja.txt'))
            ? file_get_contents(resource_path('kopi_senja.txt'))
            : 'Cafe adalah tempat bersantai untuk semua kalangan dari anak muda sampai di kalangan orang tua juga.';
        $lokasi =
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.8398418536863!2d107.96222481477006!3d-6.414444995358054!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb5c1979b90c3%3A0x6b4c6e917d057a6e!2sPatrol%2C%20Indramayu%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1620896081465!5m2!1sen!2sid';
        if (file_exists(resource_path('hubungi_kami.json'))) {
            $json = file_get_contents(resource_path('hubungi_kami.json'));
            $data = json_decode($json, true);
            if (is_array($data)) {
                $hubungi = array_merge($hubungi, $data);
            }
        }
        if (file_exists(resource_path('lokasi.txt'))) {
            $lokasiRaw = trim(file_get_contents(resource_path('lokasi.txt')));
            if (preg_match('/<iframe[^>]*src=["\']([^"\']+)["\']/i', $lokasiRaw, $matches)) {
                $lokasi = $matches[1];
            } elseif (!empty($lokasiRaw)) {
                $lokasi = $lokasiRaw;
            }
        }
    @endphp

    <main class="flex-grow">
        <section class="relative h-[calc(100vh-64px)] w-full overflow-hidden" id="hero-carousel" data-home-section="hero">
            <div class="relative h-full w-full">
                @php
                    $imageUrl = asset('images/DashboardUsr.png');
                @endphp

                <div class="absolute inset-0">
                    <img src="{{ $imageUrl }}" class="h-full w-full object-cover" alt="Foto Home Pelanggan">
                </div>

                <div class="absolute inset-0 z-[15] bg-black/40"></div>

                <div class="absolute inset-0 z-20 flex flex-col items-center justify-center px-4 text-center">
                    <h1 class="mb-4 text-4xl font-bold text-white drop-shadow-lg md:text-6xl">
                        Creating Warmth and Memories
                    </h1>
                    <p class="mb-8 max-w-2xl text-lg text-gray-200 drop-shadow-md md:text-xl">
                        Kopi Satu Bang @HoodHGL menciptakan suasana yang nyaman dan hangat, tempat di mana setiap kunjungan menjadi pengalaman yang menyenangkan, setiap percakapan terasa lebih bermakna, dan setiap momen layak untuk dikenang. 
                        Rencanakan momen spesial Anda bersama kami. Lakukan reservasi dan nikmati suasana hangat dengan lebih tenang.
                    </p>

                    <a href="{{ route('menu-makanan') }}"
                        class="inline-block rounded-full bg-blue-600 px-12 py-4 font-semibold text-white shadow-2xl transition duration-300 ease-in-out hover:scale-105 hover:bg-blue-700">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </section>

        <section id="saran-kritik" class="bg-background-light py-16" data-home-section="saran-kritik">
            <div class="container mx-auto px-6">
                <div class="mx-auto max-w-4xl rounded-3xl bg-white p-8 shadow-md">
                    <div class="mb-6 text-center">
                        <h2 class="font-display text-4xl font-bold text-primary">Kritik & Saran</h2>
                        <p class="mt-3 text-text-muted-light">
                            Bantu kami meningkatkan pelayanan dengan masukan, saran, atau kritik Anda.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-2xl bg-background-light p-5">
                            <h3 class="text-lg font-semibold text-primary">Hal yang Dapat Disampaikan</h3>
                            <ul class="mt-3 space-y-2 text-sm text-text-muted-light">
                                <li>Penilaian terhadap kualitas makanan dan minuman.</li>
                                <li>Pengalaman terkait kenyamanan tempat dan pelayanan staf.</li>
                                <li>Masukan dan saran untuk pengembangan fitur pemesanan serta reservasi.</li>
                            </ul>
                        </div>

                        <div class="rounded-2xl bg-background-light p-5">
                            <h3 class="text-lg font-semibold text-primary">Kirim Masukan</h3>
                            <p class="mt-3 text-sm text-text-muted-light">
                                Jangan ragu menghubungi kami melalui WhatsApp. Kami siap mendengarkan setiap masukan Anda dengan senang hati.
                            <a href="https://wa.me/{{ $contactPhone }}?text=Saya%20ingin%20memberikan%20saran%20dan%20kritik"
                                class="mt-5 inline-flex rounded-full bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                                Kirim via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="kontak" class="bg-white py-16" data-home-section="kontak">
            <div class="container mx-auto px-6">
                <div class="mx-auto max-w-5xl">
                    <div class="mb-8 text-center">
                        <h2 class="font-display text-4xl font-bold text-primary">Kontak</h2>
                        <p class="mt-3 text-text-muted-light">
                            Silakan hubungi kami jika Anda memiliki pertanyaan dan kebutuhan informasi lainnya terkait layanan kafe.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="rounded-2xl bg-background-light p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-primary">Telepon 1</h3>
                            <p class="mt-3 text-sm text-text-muted-light">
                                {{-- {{ $hubungi['telepon1'] }} --}}
                                <a href="http://wa.me/{{ str_replace(['+', '-'], '', $hubungi['telepon1']) }}?text=Halo%saya%20ingin%20bertanya%20tentang%20reservasi."
                                    class="text-blue-400 underline">
                                    {{ $hubungi['telepon1'] }}
                                </a>
                            </p>
                        </div>
                        <div class="rounded-2xl bg-background-light p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-primary">Email</h3>
                            <p class="mt-3 text-sm text-text-muted-light">
                                <a href="https://google.com{{ $hubungi['Email'] }}" class="text-blue-400 underline" target="_blank">
                                    {{ $hubungi['Email'] }}
                                </a>
                            </p>
                        </div>
                        <div class="rounded-2xl bg-background-light p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-primary">Alamat</h3>
                            <p class="mt-3 text-sm text-text-muted-light">{{ $hubungi['alamat'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
     <footer class="border-t border-primary/10 bg-card-light py-6" data-home-footer>
            <div class="container mx-auto px-6 text-center">
                <p class="text-sm text-text-muted-light">
                    Hak Cipta© Copyright 2026. Kopi Satu Bang @HoodHGL.
                </p>
            </div>
        </footer>
    </main>
@endsection

@push('extra-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('[data-home-section]');
            const siteFooter = document.getElementById('site-footer');
            const pageFooter = document.querySelector('[data-home-footer]');
            const validSections = ['hero', 'saran-kritik', 'kontak'];

            function applySectionVisibility() {
                const hash = window.location.hash.replace('#', '');
                const activeSectionName = validSections.includes(hash) ? hash : 'hero';

                sections.forEach(section => {
                    section.classList.toggle('hidden', section.dataset.homeSection !== activeSectionName);
                });

                if (siteFooter) {
                    siteFooter.classList.add('hidden');
                }
                if (pageFooter) {
                    pageFooter.classList.remove('hidden');
                }

                const activeSection = activeSectionName === 'hero'
                    ? document.getElementById('hero-carousel')
                    : document.getElementById(activeSectionName);

                if (activeSection) {
                    activeSection.scrollIntoView({
                        behavior: 'instant',
                        block: 'start'
                    });
                }
            }

            applySectionVisibility();
            window.addEventListener('hashchange', applySectionVisibility);
        });
    </script>
@endpush
