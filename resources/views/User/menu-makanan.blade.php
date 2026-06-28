@extends('layouts.app')

@section('title', 'Makanan')

@section('content')
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10">

        <!-- Judul -->
        <h1 class="text-center text-xl sm:text-2xl md:text-3xl font-bold mb-5 sm:mb-8">
            Menu Makanan
        </h1>

        <div class="mb-6">
            <p class="font-bold text-gray-600 mb-6">
            - Heavy Meal
            </p>
            <!-- Grid Menu Heavy Meal -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
    
                @forelse ($menuMakananHeavy as $menu)
                    @if ($menu->stok > 0)
                        {{-- MENU AKTIF --}}
                        <a href="{{ route('detail-makanan', $menu->id_menu) }}"
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden group">
                    @else
                            {{-- MENU HABIS --}}
                            <div
                                class="bg-white rounded-lg shadow-sm overflow-hidden
                        opacity-60 cursor-not-allowed relative">
                    @endif
    
                    <!-- OVERLAY HABIS -->
                    @if ($menu->stok == 0)
                        <div
                            class="absolute inset-0 bg-black/50 z-10
                            flex items-center justify-center text-white
                            font-bold text-sm sm:text-base">
                            HABIS
                        </div>
                    @endif
    
    
                    <!-- Gambar -->
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                            class="w-full h-full object-cover
                           {{ $menu->stok > 0 ? 'group-hover:scale-105 transition' : '' }}">
                    </div>
    
                    <!-- Konten -->
                    <div class="p-2 sm:p-4 text-center">
                        <h3 class="font-medium text-sm sm:text-base line-clamp-2">
                            {{ $menu->nama_menu }}
                        </h3>
    
                        <p class="text-green-600 font-semibold text-sm sm:text-base mt-1">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
    
                        {{-- INFO STOK --}}
                        @if ($menu->stok == 0)
                            <p class="text-xs text-red-500 mt-1">Stok habis</p>
                        @endif
                    </div>
    
                    @if ($menu->stok > 0)
                        </a>
                    @else
            </div>
            @endif
             @empty
                <p class="text-gray-500 col-span-full text-center">
                    Belum ada menu Heavy Meal tersedia.
                </p>
            @endforelse
        </div>
        
         <div class="mt-10 w-full h-auto clear-both block relative">
            <p class="font-bold text-gray-600 mb-6">
            - Dessert 
            </p>
            <!-- Grid Menu Dessert -->
           <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5 h-auto">
    
                @forelse ($Dessert as $menu)
                    @if ($menu->stok > 0)
                        {{-- MENU AKTIF --}}
                        <a href="{{ route('detail-makanan', $menu->id_menu) }}"
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden group">
                    @else
                            {{-- MENU HABIS --}}
                            <div
                                class="bg-white rounded-lg shadow-sm overflow-hidden
                        opacity-60 cursor-not-allowed relative">
                    @endif
    
                    <!-- OVERLAY HABIS -->
                    @if ($menu->stok == 0)
                        <div
                            class="absolute inset-0 bg-black/50 z-10
                            flex items-center justify-center text-white
                            font-bold text-sm sm:text-base">
                            HABIS
                        </div>
                    @endif
    
    
                    <!-- Gambar -->
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                            class="w-full h-full object-cover
                           {{ $menu->stok > 0 ? 'group-hover:scale-105 transition' : '' }}">
                    </div>
    
                    <!-- Konten -->
                    <div class="p-2 sm:p-4 text-center">
                        <h3 class="font-medium text-sm sm:text-base line-clamp-2">
                            {{ $menu->nama_menu }}
                        </h3>
    
                        <p class="text-green-600 font-semibold text-sm sm:text-base mt-1">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
    
                        {{-- INFO STOK --}}
                        @if ($menu->stok == 0)
                            <p class="text-xs text-red-500 mt-1">Stok habis</p>
                        @endif
                    </div>
    
                    @if ($menu->stok > 0)
                        </a>
                    @else
            </div>
            @endif
             @empty
                <p class="text-gray-500 col-span-full text-center">
                    Belum ada menu Dessert tersedia.
                </p>
            @endforelse
               
        </div>

        <div class="mt-10 w-full h-auto clear-both block relative">
            <p class="font-bold text-gray-600 mb-6">
            - Appetizer
            </p>
            <!-- Grid Menu Dessert -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5 h-auto">
    
                @forelse ($Appetizer as $menu)
                    @if ($menu->stok > 0)
                        {{-- MENU AKTIF --}}
                        <a href="{{ route('detail-makanan', $menu->id_menu) }}"
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden group">
                    @else
                            {{-- MENU HABIS --}}
                            <div
                                class="bg-white rounded-lg shadow-sm overflow-hidden
                        opacity-60 cursor-not-allowed relative">
                    @endif
    
                    <!-- OVERLAY HABIS -->
                    @if ($menu->stok == 0)
                        <div
                            class="absolute inset-0 bg-black/50 z-10
                            flex items-center justify-center text-white
                            font-bold text-sm sm:text-base">
                            HABIS
                        </div>
                    @endif
    
    
                    <!-- Gambar -->
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                            class="w-full h-full object-cover
                           {{ $menu->stok > 0 ? 'group-hover:scale-105 transition' : '' }}">
                    </div>
    
                    <!-- Konten -->
                    <div class="p-2 sm:p-4 text-center">
                        <h3 class="font-medium text-sm sm:text-base line-clamp-2">
                            {{ $menu->nama_menu }}
                        </h3>
    
                        <p class="text-green-600 font-semibold text-sm sm:text-base mt-1">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
    
                        {{-- INFO STOK --}}
                        @if ($menu->stok == 0)
                            <p class="text-xs text-red-500 mt-1">Stok habis</p>
                        @endif
                    </div>
    
                    @if ($menu->stok > 0)
                        </a>
                    @else
            </div>
            @endif
            @empty
                <p class="text-gray-500 col-span-full text-center">
                    Belum ada menu Appetizer tersedia.
                </p>
            @endforelse
        </div>

    </div>

    </div>
@endsection
