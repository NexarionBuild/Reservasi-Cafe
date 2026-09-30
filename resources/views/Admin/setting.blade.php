@extends('layouts.admin')

@section('title', 'Setting')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/menu-admin.css') }}">
@endpush

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <h2 class="text-2xl font-bold text-black mb-6">
        Pengaturan Caffe
    </h2>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow mb-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined">error</span>
                <span class="font-semibold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-7xl mx-auto space-y-10">

        {{-- ================= SLIDE FOTO ================= --}}
        <div>
            <div class="bg-white rounded-xl shadow px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-black text-center">
                    Slide Foto Beranda
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-white p-6 rounded-xl shadow">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="bg-background-light rounded-xl shadow p-5">
                        <h4 class="font-semibold text-primary mb-4">
                            Edit Foto Slide {{ $i }}
                        </h4>

                        <form action="{{ route('update-foto-slide', ['slide' => $i]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <img src="{{ asset('images/slide' . $i . '.jpg') }}" alt="Slide {{ $i }}"
                                class="w-full aspect-video object-cover rounded-lg border mb-3 text-black">

                            <input type="file" name="foto_slide"
                                class="w-full text-sm text-muted
                               file:mr-3 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:bg-primary file:text-white
                               hover:file:bg-primary/90">

                            <button type="submit" class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                                Simpan Foto
                            </button>
                        </form>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ================= GAMBAR RUANGAN ================= --}}
        <div>
            <div class="bg-white rounded-xl shadow px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-black text-center">
                    Gamabar Ruangan
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-white p-6 rounded-xl shadow">
                @for ($i = 1; $i <= 2; $i++)
                    <div class="bg-background-light rounded-xl shadow p-5">
                        <h4 class="font-semibold text-primary mb-4">
                            Edit Gambar Ruangan {{ $i === 1 ? 'Indoor' : 'Outdoor' }}
                        </h4>

                        <form action="{{ route('update-foto-ruangan', ['ruangan' => $i]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <img src="{{ asset('images/ruangan' . $i . '.jpg') }}" alt="ruangan {{ $i === 1 ? 'Indoor' : 'Outdoor' }}"
                                class="w-full aspect-video object-cover rounded-lg border mb-3 text-black">

                            <input type="file" name="foto_ruangan"
                                class="w-full text-sm text-muted
                               file:mr-3 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:bg-primary file:text-white
                               hover:file:bg-primary/90">

                            <button type="submit" class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                                Simpan Foto
                            </button>
                        </form>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ================= TENTANG KAMI ================= --}}
        <div>
            <div class="bg-white rounded-xl shadow px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-black text-center">
                    Tentang Kami
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-white p-6 rounded-xl shadow">

                {{-- Foto Tentang --}}
                <div class="bg-background-light rounded-xl shadow p-5">
                    <h4 class="font-semibold text-primary mb-4">
                        Foto Tentang Kami
                    </h4>

                    <form action="{{ route('update-foto-tentang') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <img src="{{ asset('images/foto_tentang.jpg') }}"
                            class="w-full aspect-video object-cover rounded-lg border mb-3 text-black" alt="Foto Tentang Kami">

                        <input type="file" name="foto_tentang"
                            class="w-full text-sm text-muted
                               file:mr-3 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:bg-primary file:text-white
                               hover:file:bg-primary/90">

                        <button class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan Foto
                        </button>
                    </form>
                </div>

                {{-- Text Tentang --}}
                <div class="bg-background-light rounded-xl shadow p-5">
                    <h4 class="font-semibold text-primary mb-4">
                        Deskripsi Tentang Kami
                    </h4>

                    <form action="{{ route('update-tentang-kami') }}" method="POST">
                        @csrf

                        <textarea rows="6" name="tentang_kami"
                            class="w-full border rounded-lg p-3 resize-none focus:outline-primary text-sm text-black">
                        {{ old('tentang_kami', file_exists(resource_path('tentang_kami.txt')) ? file_get_contents(resource_path('tentang_kami.txt')) : '') }}
                        </textarea>

                        <button class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan
                        </button>
                    </form>
                </div>

                {{-- Kopi Senja --}}
                <div class="bg-background-light rounded-xl shadow p-5">
                    <h4 class="font-semibold text-primary mb-4">
                        Kopi Senja
                    </h4>

                    <form action="{{ route('update-kopi-senja') }}" method="POST">
                        @csrf

                        <textarea rows="4" name="kopi_senja"
                            class="w-full border rounded-lg p-3 resize-none focus:outline-primary text-sm text-black">
                            {{ old('kopi_senja', file_exists(resource_path('kopi_senja.txt')) ? file_get_contents(resource_path('kopi_senja.txt')) : '') }}
                        </textarea>

                        <button class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan
                        </button>
                    </form>
                </div>

            </div>
        </div>


        {{-- ================= QRIS PAYMENT ================= --}}
        <div>
            <div class="bg-white rounded-xl shadow px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-black text-center">
                    Pembayaran QRIS
                </h3>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <div class="bg-background-light rounded-xl shadow p-6 max-w-md mx-auto">
                    <h4 class="font-semibold text-primary mb-4">
                        QR Code QRIS
                    </h4>

                    <form action="{{ route('update-qris') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('images/qrcode.png') }}" alt="QRIS QR Code"
                                class="w-48 h-48 object-contain rounded-lg border bg-white p-2">
                        </div>

                        <input type="file" name="qris_image" accept="image/*"
                            class="w-full text-sm text-muted
                               file:mr-3 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:bg-primary file:text-white
                               hover:file:bg-primary/90">

                        <p class="text-xs text-muted mt-2 mb-4">
                            Upload gambar QR Code QRIS untuk pembayaran. Format: PNG, JPG, JPEG (Max: 2MB)
                        </p>

                        <button type="submit" class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan QR Code
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ================= HUBUNGI & LOKASI ================= --}}

        <div>
            <div class="bg-white rounded-xl shadow px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-black text-center">
                    Footer
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow">

                {{-- Hubungi Kami --}}
                <div class="bg-background-light rounded-xl shadow p-6">
                    <h4 class="font-semibold text-primary mb-4">
                        Hubungi Kami
                    </h4>

                    @php
                        $hubungi = [
                            'telepon1' => '+62-812-1234-1234',
                            'telepon2' => '+62-812-1234-1234',
                            'alamat' => 'Jl. Nasional 1 Patrol',
                        ];

                        if (file_exists(resource_path('hubungi_kami.json'))) {
                            $hubungi = array_merge(
                                $hubungi,
                                json_decode(file_get_contents(resource_path('hubungi_kami.json')), true),
                            );
                        }
                    @endphp

                    <form action="{{ route('update-hubungi-kami') }}" method="POST">
                        @csrf

                        <input type="text" name="telepon[]" class="input mb-2 text-black" value="{{ $hubungi['telepon1'] }}">
                        <input type="text" name="telepon[]" class="input mb-2 text-black" value="{{ $hubungi['telepon2'] }}">
                        <input type="text" name="alamat" class="input text-black" value="{{ $hubungi['alamat'] }}">

                        <button class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan
                        </button>
                    </form>
                </div>

                {{-- Lokasi --}}
                <div class="bg-background-light rounded-xl shadow p-6">
                    <h4 class="font-semibold text-primary mb-4">
                        Lokasi Caffe
                    </h4>

                    <form action="{{ route('update-lokasi') }}" method="POST">
                        @csrf

                        <input type="text" name="maps_link" class="input mb-4 text-black"
                            value="{{ old('maps_link', $lokasi ?? '') }}"
                            placeholder="https://www.google.com/maps/embed?...">

                        @if (!empty($lokasi))
                            <div class="aspect-video rounded-lg overflow-hidden border">
                                <iframe src="{{ $lokasi }}" class="w-full h-full border-0" loading="lazy"></iframe>
                            </div>
                        @endif

                        <button class="btn-primary w-full mt-4 text-white text-sm py-2 bg-accent rounded-lg">
                            Simpan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
