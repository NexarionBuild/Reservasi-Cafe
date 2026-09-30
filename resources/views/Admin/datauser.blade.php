@extends('layouts.admin')

@section('title', 'Makanan')

@section('content')


<!-- TITLE -->
<h2 class="text-2xl font-bold text-gray-800">
    Data User
</h2>

    <div>
        <!-- TOP ACTIONS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between  bg-white gap-4 py-4 px-5 mb-3 rounded-lg shadow ">
    
            <!-- SEARCH -->
            <form method="GET" class="w-full sm:w-64">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search menu..."
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
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Nomor Telepon</th>
                        {{-- <th class="px-4 py-3 text-left">Tgl Reservasi</th> --}}
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
    
                <!-- BODY -->
                <tbody class="divide-y">
                     @if ($dataUser->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @else
                        @foreach ($dataUser as $u)
                            <tr class="hover:bg-gray-50">
        
                                <td class="px-4 py-3 text-center">
                                    {{ ($dataUser->currentPage() - 1) * $dataUser->perPage() + $loop->iteration }}
                                </td>
        
                                <td class="px-4 py-3">
                                {{ $u->nama_pelanggan }}
                                </td>
        
                                <td class="px-4 py-3">{{ $u->email ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $u->no_telepon }}
                                </td>
        
                                {{-- <td class="px-4 py-3">
                                    {{ $u->created_at }}
                                </td> --}}
        
                                <td class="px-4 py-3 text-center text-gray-400">Tidak dapat diedit</td>
        
                            </tr>
                        @endforeach
                    @endif
                    
                </tbody>
    
            </table>
        </div>
        <!-- PAGINATION -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm mt-8">
    
            <!-- SHOW ENTRIES -->
            <form method="GET" class="flex items-center gap-2 bg-primary px-3 py-1 rounded-lg">
                Show
                <select name="per_page"
                    onchange="this.form.submit()"
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
                <a href="{{ $dataUser->previousPageUrl() ?? '#' }}"
                class="px-3 py-1 rounded
                {{ $dataUser->onFirstPage()
                        ? 'bg-gray-300 cursor-not-allowed'
                        : 'bg-primary hover:bg-primary/90 text-white' }}">
                    Previous
                </a>

                <!-- CURRENT PAGE -->
                <span class="px-3 py-1 bg-primary text-white rounded">
                    {{ $dataUser->currentPage() }}
                </span>

                <!-- NEXT -->
                <a href="{{ $dataUser->nextPageUrl() ?? '#' }}"
                class="px-3 py-1 rounded
                {{ $dataUser->hasMorePages()
                        ? 'bg-primary hover:bg-primary/90 text-white'
                        : 'bg-gray-300 cursor-not-allowed' }}">
                    Next
                </a>

            </div>
    
        </div>
    </div>


@endsection
