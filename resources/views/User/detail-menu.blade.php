@extends('layouts.app')

@section('title', 'Detail-Menu')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detail-menu.css') }}">
@endpush

@section('content')

    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center md:text-center">
            Detail Menu
        </h1>

        @foreach ($chosedMenu as $index => $menu)
            @php
                $isDrink = ($menu['kategori'] ?? null) === 'minuman';
                $isHeavyMeal = ($menu['jenis'] ?? null) === 'Heavy Meal';
                $drinkOptions = $menu['options'] ?? [];
                $gambarPath = $menu['gambar'] ?? '';
                if (\Illuminate\Support\Str::startsWith($gambarPath, ['http://', 'https://'])) {
                    $gambarUrl = $gambarPath;
                } elseif (\Illuminate\Support\Str::startsWith($gambarPath, 'storage/')) {
                    $gambarUrl = asset($gambarPath);
                } else {
                    $gambarUrl = asset('storage/' . ltrim($gambarPath, '/'));
                }
            @endphp
            <div class="flex flex-col md:flex-row gap-6 md:gap-10 bg-white shadow-md rounded-xl p-4 md:p-6 mb-8">

                <!-- Gambar Menu -->
                <div class="w-full md:w-1/2">
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                        class="w-full h-64 md:h-full object-cover rounded-lg">
                </div>

                <!-- Deskripsi Menu -->
                <div class="w-full md:w-1/2 flex flex-col justify-between">
                    <form action="{{ route('add-to-cart') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <div class="flex justify-between">
                            <div>
                                <h2 class="text-xl md:text-2xl font-semibold mb-2">
                                    {{ $menu->nama_menu }}
                                </h2>

                                <p class="text-gray-600 mb-3 leading-relaxed">
                                    {{ $menu->deskripsi }}
                                </p>

                                <p class="text-base md:text-lg">
                                    Harga:
                                    <span class="font-bold text-green-600">
                                        Rp {{ number_format($menu->harga) }}
                                    </span>
                                </p>
                                <p class="mt-2 text-sm {{ $menu->stok > 0 ? 'text-gray-600' : 'text-red-600 font-semibold' }}">Sisa stok: {{ $menu->stok }}</p>
                            </div>

                            {{-- Sisi Kanan: Seluruh Tombol Opsi Minuman Berjajar --}}
                            @if ($isDrink)
                            {{-- <div class="flex flex-col gap-2 text-right">
                                <div class="grid grid-cols-2 gap-2 min-w-[180px]">
                                    @foreach ($drinkOptionLabels['temperature'] as $value => $label)
                                        <button type="button"
                                            class="drink-option-btn flex flex-row items-center justify-center gap-2 rounded-xl border-2 px-3 py-2 text-center transition {{ ($drinkOptions['temperature'] ?? 'ice') === $value ? 'border-amber-500 bg-white text-slate-900 shadow-sm' : 'border-transparent bg-slate-50 text-slate-400' }}"
                                            data-index="{{ $index }}" data-option="temperature"
                                            data-value="{{ $value }}">
                                            <span class="text-base">{{ $value === 'ice' ? '🧊' : '☕' }}</span>
                                            <span class="text-xs font-bold">{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>

                               
                                <div class="grid grid-cols-2 gap-2 min-w-[180px]">
                                    @foreach ($drinkOptionLabels['sugar_level'] as $value => $label)
                                        <button type="button"
                                            class="drink-option-btn flex flex-row items-center justify-center gap-2 rounded-xl border-2 px-3 py-2 text-center transition {{ ($drinkOptions['sugar_level'] ?? 'normal_sugar') === $value ? 'border-amber-500 bg-white text-slate-900 shadow-sm' : 'border-transparent bg-slate-50 text-slate-400' }}"
                                            data-index="{{ $index }}" data-option="sugar_level"
                                            data-value="{{ $value }}">
                                            <span class="text-xs font-bold">{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                
                                <div class="grid grid-cols-3 gap-2 min-w-[180px]">
                                    @foreach ($drinkOptionLabels['ice_level'] as $value => $label)
                                        <button type="button"
                                            class="drink-option-btn flex flex-row items-center justify-center gap-1 rounded-xl border-2 px-1 py-2 text-center transition {{ ($drinkOptions['ice_level'] ?? 'normal_ice') === $value ? 'border-amber-500 bg-white text-slate-900 shadow-sm' : 'border-transparent bg-slate-50 text-slate-400' }}"
                                            data-index="{{ $index }}" data-option="ice_level"
                                            data-value="{{ $value }}">
                                            <span class="text-[11px] font-bold leading-tight">{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div> --}}

                                <div class="flex-col">
                                    <div class="mb-5">
                                        <h3 class="font-semibold mb-2">Temperature</h3>

                                        <div class="grid grid-cols-2 gap-2">

                                            <input class="hidden peer/ice" type="radio" id="ice" name="temperature"
                                                value="ice" checked>

                                            <label for="ice"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center peer-checked/ice:border-blue-600 peer-checked/ice:bg-blue-600 peer-checked/ice:text-white">
                                                🧊 Ice
                                            </label>


                                            <input class="hidden peer/hot" type="radio" id="hot" name="temperature"
                                                value="hot">

                                            <label for="hot"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center peer-checked/hot:border-red-600 peer-checked/hot:bg-red-600 peer-checked/hot:text-white">
                                                ☕ Hot
                                            </label>

                                        </div>

                                    </div>

                                    <div class="mb-5">
                                        <h3 class="font-semibold mb-2">Sugar</h3>

                                        <div class="grid grid-cols-2 gap-2">

                                            <input class="hidden peer/ns" type="radio" id="normal_sugar"
                                                name="sugar_level" value="normal sugar" checked>

                                            <label for="normal_sugar"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center
                                            peer-checked/ns:bg-green-600
                                            peer-checked/ns:text-white
                                            peer-checked/ns:border-green-600">
                                                Normal Sugar
                                            </label>


                                            <input class="hidden peer/ls" type="radio" id="less_sugar" name="sugar_level"
                                                value="less sugar">

                                            <label for="less_sugar"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center
                                            peer-checked/ls:bg-green-600
                                            peer-checked/ls:text-white
                                            peer-checked/ls:border-green-600">
                                                Less Sugar
                                            </label>

                                        </div>

                                    </div>

                                    <div class="mb-5">

                                        <h3 class="font-semibold mb-2">Ice Level</h3>

                                        <div class="grid grid-cols-3 gap-2">

                                            <input class="hidden peer/i1" type="radio" id="normal_ice" name="ice_level"
                                                value="normal ice" checked>

                                            <label for="normal_ice"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-2 py-3 text-center
                                            peer-checked/i1:bg-cyan-600
                                            peer-checked/i1:text-white
                                            peer-checked/i1:border-cyan-600">
                                                Normal
                                            </label>


                                            <input class="hidden peer/i2" type="radio" id="less_ice" name="ice_level"
                                                value="less ice">

                                            <label for="less_ice"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-2 py-3 text-center
                                            peer-checked/i2:bg-cyan-600
                                            peer-checked/i2:text-white
                                            peer-checked/i2:border-cyan-600">
                                                Less
                                            </label>


                                            <input class="hidden peer/i3" type="radio" id="no_ice" name="ice_level"
                                                value="no ice">

                                            <label for="no_ice"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-2 py-3 text-center
                                            peer-checked/i3:bg-cyan-600
                                            peer-checked/i3:text-white
                                            peer-checked/i3:border-cyan-600">
                                                No Ice
                                            </label>

                                        </div>

                                    </div>
                                </div>
                            @endif
                             {{-- Sisi Kanan: Seluruh Tombol Opsi Minuman Berjajar --}}
                            @if ($isHeavyMeal)
                                <div class="flex-col">
                                    <div class="mb-5">
                                        <h3 class="font-semibold mb-2">level Pedas</h3>

                                        <div class="grid grid-cols-2 gap-2">

                                            <input class="hidden peer/ice" type="radio" id="tidak_pedas" name="level_pedas"
                                                value="tidak pedas" checked>

                                            <label for="tidak_pedas"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center peer-checked/ice:border-blue-600 peer-checked/ice:bg-blue-600 peer-checked/ice:text-white">
                                                tidak pedas
                                            </label>

                                            <input class="hidden peer/Sedang" type="radio" id="Sedang" name="level_pedas"
                                                value="sedang">

                                            <label for="Sedang"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center peer-checked/Sedang:border-gray-600 peer-checked/Sedang:bg-gray-600 peer-checked/Sedang:text-white">
                                                sedang
                                            </label>

                                            <input class="hidden peer/hot" type="radio" id="Pedas" name="level_pedas"
                                                value="Pedas">

                                            <label for="Pedas"
                                                class="cursor-pointer rounded-xl border-2 border-gray-300 px-4 py-3 text-center peer-checked/hot:border-red-600 peer-checked/hot:bg-red-600 peer-checked/hot:text-white">
                                                Pedas
                                            </label>

                                        </div>

                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            {{-- <form action="{{ route('add-to-cart') }}" method="POST" class="w-full sm:w-auto"> --}}


                            <input type="hidden" name="id" value="{{ $menu->id_menu }}">
                            <input type="hidden" name="nama" value="{{ $menu->nama_menu }}">
                            <input type="hidden" name="harga" value="{{ $menu->harga }}">
                            <input type="hidden" name="gambar" value="{{ $menu->gambar }}">
                            {{-- <input type="hidden" name="qty" value="{{ $menu->qty }}"> --}}

                            <button type="submit" {{ $menu->stok <= 0 ? 'disabled' : '' }}
                                class="w-full sm:w-auto {{ $menu->stok > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-6 py-2 rounded-lg transition duration-200">
                                + Keranjang
                            </button>

                        </div>
                    </form>

                </div>

            </div>
        @endforeach
    </div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const ctx = document.getElementById("myChart").getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "Mei"],
                    datasets: [{
                        label: "Pelanggan",
                        data: [5, 9, 7, 12, 10],
                        borderWidth: 2,
                        borderColor: "blue"
                    }]
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".drink-option-btn").forEach(button => {

                button.addEventListener("click", function() {

                    const option = this.dataset.option;
                    const value = this.dataset.value;
                    const index = this.dataset.index;

                    // Reset semua tombol dalam grup yang sama
                    document.querySelectorAll(
                        `.drink-option-btn[data-index="${index}"][data-option="${option}"]`
                    ).forEach(btn => {

                        btn.classList.remove(
                            "border-amber-500",
                            "bg-white",
                            "text-slate-900",
                            "shadow-sm"
                        );

                        btn.classList.add(
                            "border-transparent",
                            "bg-slate-50",
                            "text-slate-400"
                        );

                    });

                    // Aktifkan tombol yang dipilih
                    this.classList.remove(
                        "border-transparent",
                        "bg-slate-50",
                        "text-slate-400"
                    );

                    this.classList.add(
                        "border-amber-500",
                        "bg-white",
                        "text-slate-900",
                        "shadow-sm"
                    );

                    // Simpan ke hidden input dalam form yang sama
                    const form = this.closest(".flex.flex-col.md\\:flex-row").querySelector("form");

                    if (form) {
                        const hidden = form.querySelector(`input[name="${option}"]`);

                        if (hidden) {
                            hidden.value = value;
                        }
                    }

                });

            });

        });
    </script>
@endpush
