@extends('layouts.admin')

@section('title', 'Makanan')

@section('content')

    @if (session('success'))
        <div class="mb-4 flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md animate-pulse"
            role="alert">
            <span class="material-symbols-outlined mr-2">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('gagal'))
        <div class="mb-4 flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md animate-bounce"
            role="alert">
            <span class="material-icons mr-2">error_outline</span>
            <p class="font-medium">{{ session('gagal') }}</p>
        </div>
    @endif

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-gray-800">
        Data Reservasi
    </h2>

    <div>
        <!-- TOP ACTIONS -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between  bg-white gap-4 py-4 px-5 mb-3 rounded-lg shadow ">

            <!-- ADD BUTTON -->
            {{-- <a href="{{ route('formMakanan') }}"
                class="inline-flex items-center justify-center
                  bg-primary hover:bg-primary/90
                  text-white font-semibold
                  px-4 py-2 rounded-lg
                  transition w-full sm:w-auto">
                + Menu
            </a> --}}

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
                        <th class="px-4 py-3 text-center">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-center">Tanggal Reservasi</th>
                        <th class="px-4 py-3 text-center">Jumlah Tamu</th>
                        <th class="px-4 py-3 text-center">Ruangan</th>
                        <th class="px-4 py-3 text-center">Meja</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y">
                    @if ($reservasi->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @else
                        @foreach ($reservasi as $r)
                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3 text-center">
                                    {{ ($reservasi->currentPage() - 1) * $reservasi->perPage() + $loop->iteration }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $r->pelanggan->nama_pelanggan ?? '-' }}
                                </td>

                                <td class="px-4 py-3 font-medium text-center">
                                    {{ $r->tanggal }}
                                </td>

                                <td class="py-3 text-center">
                                    {{ $r->jumlah_tamu }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $r->ruangan }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $r->kode_meja ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">

                                        <form action="{{ route('kosongkan.meja') }}" method="post">
                                            @csrf

                                            <input type="text" name="id_reservasi" class="hidden"
                                                value="{{ $r->id_reservasi }}">
                                            <button type="submit" {{ $r->status === 'tervalidasi' ? '' : 'disabled' }}
                                                class="px-2 py-2 rounded-md
                                                {{ $r->status === 'tervalidasi' ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 hover:bg-gray-300' }}
                                                text-white transition">
                                                <span class="material-symbols-outlined">check</span>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openModal({{ $r->id_reservasi }})"
                                            class="px-2 py-2 rounded-md bg-blue-500 hover:bg-blue-600
                                        text-white transition text-white">
                                            <span class="material-symbols-outlined text-[20px]">
                                                info
                                            </span>
                                        </button>

                                        <form action="{{ route('hapusReservasi', $r->id_reservasi) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus reservasi ini?')">
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
                    @endif
                </tbody>
            </table>

            <!-- MODAL DETAIL -->
            <div id="modalDetail"
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 overflow-auto text-gray-800">

                <div id="modalContentWrapper"
                    class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative transform scale-95 opacity-0 transition-all duration-200 ease-in-out">
                    <!-- CLOSE BUTTON -->
                    <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">
                        &times;
                    </button>

                    <h2 class="text-lg font-semibold mb-4">Detail Pesanan</h2>

                    <!-- CONTENT -->
                    <div id="modalContent" class="space-y-2 text-sm">
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
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
                <a href="{{ $reservasi->previousPageUrl() ?? '#' }}"
                    class="px-3 py-1 rounded
                {{ $reservasi->onFirstPage() ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary hover:bg-primary/90 text-white' }}">
                    Previous
                </a>

                <!-- CURRENT PAGE -->
                <span class="px-3 py-1 bg-primary text-white rounded">
                    {{ $reservasi->currentPage() }}
                </span>

                <!-- NEXT -->
                <a href="{{ $reservasi->nextPageUrl() ?? '#' }}"
                    class="px-3 py-1 rounded
                {{ $reservasi->hasMorePages() ? 'bg-primary hover:bg-primary/90 text-white' : 'bg-gray-300 cursor-not-allowed' }}">
                    Next
                </a>

            </div>

        </div>
    </div>

@endsection

@push('extra-scripts')
    <script>
        function openModal(id) {
            const modal = document.getElementById('modalDetail');
            const wrapper = document.getElementById('modalContentWrapper');
            const content = document.getElementById('modalContent');

            // Tampilkan overlay
            modal.classList.remove('hidden');

            // Animasi masuk
            setTimeout(() => {
                wrapper.classList.remove('scale-95', 'opacity-0');
                wrapper.classList.add('scale-100', 'opacity-100');
            }, 10);


            content.innerHTML = '<p class="text-gray-500">Memuat data...</p>';

            fetch(`/admin/data-reservasi/${id}/detail-json`)
                .then(async res => {
                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(text);
                    }
                    return res.json();
                })
                .then(data => {

                    if (data.message) {
                        content.innerHTML =
                            `<p class="text-red-500">${data.message}</p>`;
                        return;
                    }

                    let html = `
                    <p><strong>Nama:</strong> ${data.nama_pelanggan}</p>
                    <p><strong>Waktu:</strong> ${data.waktu}</p>
                    <p><strong>Jumlah Tamu:</strong> ${data.jumlah_tamu}</p>
                    <hr class="my-2">
                `;

                    // Tampilkan transaksi
                    if (data.transaksi && data.transaksi.length > 0) {
                        data.transaksi.forEach(trx => {
                            let statusText = 'Belum Dibayar';
                            let statusColor = 'text-red-500';

                            if (trx.status === 'menunggu') {
                                statusText = 'Belum Tervalidasi';
                                statusColor = 'text-yellow-500';
                            } else if (trx.status === 'tervalidasi') {
                                statusText = 'Tervalidasi';
                                statusColor = 'text-green-600';
                            } else if (trx.status === 'selesai') {
                                statusText = 'selesai';
                                statusColor = 'text-green-600';
                            }

                            html += `
                            <div class="my-2 p-2 border rounded">
                                <p><strong>Status:</strong> <span class="${statusColor} font-semibold">${statusText}</span></p>
                                <p><strong>Total:</strong> Rp ${Number(trx.total || 0).toLocaleString()}</p>
                                <p><strong>Metode:</strong> ${trx.metode_pembayaran || '-'}</p>
                        `;

                            // Pesanan terkait
                            // Pesanan (sudah digabung per menu)
                            if (data.pesanan && data.pesanan.length > 0) {
                                html += `<strong>Pesanan:</strong><ul class="list-disc ml-5">`;

                                data.pesanan.forEach(p => {
                                    html += `
                                    <li>
                                        (x${p.total_qty}) ${p.nama_menu}
                                        - Rp ${Number(p.subtotal).toLocaleString()}
                                        <br>
                                        ${p.catatan}
                                    </li>
                                    `;
                                });

                                html += `</ul>`;
                            } else {
                                html += `<p class="text-gray-400">Tidak ada pesanan</p>`;
                            }

                            // Tambahkan tombol Validasi & Hapus
                            html += `
                            <div class="mt-3 flex justify-end gap-2">
                                <button onclick="validasiTransaksi(${trx.id_transaksi})"
                                    class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
                                    Validasi
                                </button>
                            </div>
                        `;

                            html += `
                                <div class="mt-3">
                                    <p class="text-sm font-semibold text-gray-600 mb-1">Bukti Pembayaran:</p>
                                    <div class="flex justify-center border rounded-lg p-2 bg-gray-50">
                                        <img src="${window.location.origin}/storage/${data.bukti_pembayaran}" 
                                            alt="Bukti Transfer" 
                                            class="w-25 h-100 rounded shadow-sm cursor-pointer hover:scale-105 transition-transform"
                                            onclick="window.open(this.src)">
                                    </div>
                                </div>
                            `;

                            html += `</div>`; // tutup div transaksi
                        });
                    } else {
                        html += `<p class="text-gray-400">Tidak ada transaksi</p>`;
                    }

                    content.innerHTML = html;
                })
                .catch(err => {
                    console.error(err);
                    content.innerHTML =
                        `<p class="text-red-500">Gagal mengambil data ${err}</p>`;
                });
        }

        // Tutup modal
        function closeModal() {
            const modal = document.getElementById('modalDetail');
            const wrapper = document.getElementById('modalContentWrapper');
            // Animasi keluar
            wrapper.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200); // durasi sama dengan transition

        }


        // Klik di luar modal untuk menutup
        document.getElementById('modalDetail').addEventListener('click', function(e) {
            const wrapper = document.getElementById('modalContentWrapper');
            if (!wrapper.contains(e.target)) {
                closeModal();
            }
        });


        function validasiTransaksi(id) {
            if (!confirm('Yakin ingin melakukan validasi transaksi ini?')) return;

            fetch(`/admin/transaksi/${id}/validasi`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(text);
                    }
                    return res.json();
                })
                .then(data => {
                    alert(data.message);
                    closeModal();
                    location.reload(); // refresh halaman agar status terbaru terlihat
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal melakukan validasi transaksi');
                });
        }


        // ARAHKAN KE MENGHAPUS PESANAN
        function hapusTransaksi(id) {
            if (!confirm('Yakin ingin menghapus transaksi ini?')) return;

            fetch(`/admin/transaksi/${id}/hapus`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal hapus');
                    alert('Transaksi berhasil dihapus');
                    closeModal();
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal menghapus transaksi');
                });
        }
    </script>
@endpush
