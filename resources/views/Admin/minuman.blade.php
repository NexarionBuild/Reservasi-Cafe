@extends('layouts.admin')

@section('title', 'Makanan')

@section('content')

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @elseif (session('gagal'))
        <div class="mb-6 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
            {{ session('gagal') }}
        </div>
    @endif
    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-gray-800">
        Data Minuman
    </h2>

    <div>
        <!-- TOP ACTIONS -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between  bg-white gap-4 py-4 px-5 mb-3 rounded-lg shadow ">

            <!-- ADD BUTTON -->
            <a href="{{ route('formMenu', ['kategori' => 'minuman']) }}"
                class="inline-flex items-center justify-center
                  bg-primary hover:bg-primary/90
                  text-white font-semibold
                  px-4 py-2 rounded-lg
                  transition w-full sm:w-auto">
                + Menu
            </a>

            <!-- SEARCH -->
            <form method="GET" class="w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search menu..."
                    class="w-full rounded-lg border border-gray-300
                        px-4 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-primary text-black">
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto bg-white rounded-xl shadow border">

            <table class="min-w-full text-sm text-gray-700">

                <!-- HEAD -->
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3 text-center">Foto</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Deskripsi</th>
                        <th class="px-4 py-3 text-center">Stok</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y">
                    @foreach ($minuman as $menu)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3 text-center">
                                {{ ($minuman->currentPage() - 1) * $minuman->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                        class="h-14 w-14 object-cover rounded-lg border">
                                </div>
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $menu->nama_menu }}
                            </td>

                            <td class="px-4 py-3">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 max-w-xs truncate">
                                {{ $menu->jenis }}
                            </td>

                            <td class="px-4 py-3 max-w-xs truncate">
                                {{ $menu->deskripsi }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $menu->stok }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">

                                    <!-- EDIT -->
                                    <a href="{{ route('edit', $menu->id_menu) }}"
                                        class="px-2 py-2 rounded-md
                                      bg-blue-500 hover:bg-blue-600
                                      text-white transition">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('destroy', $menu->id_menu) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-2 py-2 rounded-md
                                               bg-red-500 hover:bg-red-600
                                               text-white transition">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        <!-- PAGINATION -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm mt-8">

            <!-- SHOW ENTRIES -->
            <form method="GET" class="flex items-center gap-2 bg-primary px-3 py-1 rounded-lg">
                Show
                <select name="per_page" onchange="this.form.submit()"
                    class="border rounded mx-2 py-0 focus:ring-primary focus:outline-none text-primary">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
                entries
            </form>


            <!-- PAGE CONTROL -->
            <div class="flex items-center gap-2">

                <!-- PREVIOUS -->
                <a href="{{ $minuman->previousPageUrl() ?? '#' }}"
                    class="px-3 py-1 rounded
                {{ $minuman->onFirstPage() ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary hover:bg-primary/90 text-white' }}">
                    Previous
                </a>

                <!-- CURRENT PAGE -->
                <span class="px-3 py-1 bg-primary text-white rounded">
                    {{ $minuman->currentPage() }}
                </span>

                <!-- NEXT -->
                <a href="{{ $minuman->nextPageUrl() ?? '#' }}"
                    class="px-3 py-1 rounded
                {{ $minuman->hasMorePages() ? 'bg-primary hover:bg-primary/90 text-white' : 'bg-gray-300 cursor-not-allowed' }}">
                    Next
                </a>

            </div>

        </div>
    </div>


@endsection
