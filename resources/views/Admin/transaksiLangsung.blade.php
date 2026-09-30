@extends('layouts.admin') {{-- sesuaikan nama layout kalau berbeda --}}

@section('content')
@php
$cart = $keranjang->map(function ($item) {
    return [
        'id'    => $item->id_menu,
        'name'  => $item->nama_menu,
        'price' => $item->harga,
        'qty'   => $item->qty,
        'stok'  => 999,
        'note'  => $item->catatan,
    ];
});
@endphp
    <div class="flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-500 dark:text-amber-800 mt-1">Pilih Menu</h2>
                <p class="text-sm text-gray-500 dark:text-amber-800 mt-1">Klik menu untuk menambahkan ke keranjang transaksi.
                </p>
            </div>

            {{-- Tombol buka/tutup keranjang --}}
            <button id="cartToggle" type="button"
                class="relative w-11 h-11 rounded-full flex items-center justify-center bg-primary text-white hover:bg-primary/90 transition-colors shadow-sm"
                aria-label="Buka keranjang">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span id="cartCount"
                    class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-warning text-white text-[11px] font-semibold flex items-center justify-center">0</span>
            </button>
        </div>

        {{-- Filter kategori --}}
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
            <button type="button" data-filter="all"
                class="filter-btn shrink-0 px-4 py-2 rounded-full text-sm font-medium bg-primary text-white hover:bg-primary/90">
                Semua
            </button>

            @foreach ($kategoriList as $kategori)
                <button type="button" data-filter="{{ $kategori }}"
                    class="filter-btn shrink-0 px-4 py-2 rounded-full text-sm font-medium bg-white dark:bg-card-dark text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-white/10">
                    {{ $kategori }}
                </button>
            @endforeach
        </div>

        {{-- Grid menu --}}
        <div id="menuGrid"
            class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 pb-24 md:pb-0">
            @forelse ($menus as $item)
                @php $habis = (int) $item->stok <= 0; @endphp
                <div class="menu-card bg-white dark:bg-card-dark rounded-xl overflow-hidden border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-lg transition-shadow flex flex-col"
                    data-category="{{ $item->kategori }}">
                    <div class="relative aspect-square w-full overflow-hidden bg-gray-100 dark:bg-white/5">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/400x400?text=No+Image' }}"
                            alt="{{ $item->nama_menu }}" class="w-full h-full object-cover" loading="lazy" />
                        @if ($habis)
                            <span
                                class="absolute top-2 left-2 bg-warning text-white text-[11px] font-semibold px-2 py-1 rounded-full">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                    <div class="p-3 md:p-4 flex flex-col flex-1">
                        <span class="text-xs text-primary font-medium">{{ $item->kategori }}</span>
                        <h3 class="font-semibold text-sm md:text-base mt-0.5">{{ $item->nama_menu }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex-1 line-clamp-2">{{ $item->deskripsi }}
                        </p>
                        <div class="flex items-center justify-between mt-3">
                            <span
                                class="font-bold text-sm md:text-base">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" @disabled($habis)
                                class="add-to-cart-btn w-9 h-9 rounded-full flex items-center justify-center transition-colors {{ $habis ? 'bg-gray-200 dark:bg-white/10 text-gray-400 cursor-not-allowed' : 'bg-primary text-white hover:bg-primary/90' }}"
                                data-id="{{ $item->id_menu }}" data-name="{{ $item->nama_menu }}"
                                data-price="{{ $item->harga }}" data-stok="{{ $item->stok }}"
                                aria-label="Tambah {{ $item->nama_menu }} ke keranjang">
                                <span class="material-symbols-outlined text-lg">add</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-16">
                    Belum ada menu tersedia.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============ OVERLAY KERANJANG (mobile) ============ --}}
    <div id="cartOverlay"
        class="fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

    {{-- ============ CART SIDEBAR ============ --}}
    <aside id="cartSidebar"
        class="fixed top-0 right-0 h-full w-full sm:w-96 bg-background-light dark:bg-background-aside z-50 shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">

        <div class="flex items-center justify-between px-4 md:px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <h2 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined">shopping_cart</span>
                Keranjang
            </h2>
            <button id="cartClose" type="button"
                class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-gray-200 dark:hover:bg-white/10"
                aria-label="Tutup keranjang">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div id="cartItems" class="flex-1 overflow-y-auto no-scrollbar px-4 md:px-6 py-4 space-y-3">
            <p id="cartEmptyMessage" class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10">
                Keranjang masih kosong.
            </p>
        </div>

        <div class="border-t border-gray-200 dark:border-white/10 px-4 md:px-6 py-4 space-y-3">

            {{-- Pilihan ruangan --}}
            {{-- <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Pilih Ruangan</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" data-room="Indoor"
                        class="room-btn flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-sm font-medium border transition-colors bg-primary text-white border-primary">
                        <span class="material-symbols-outlined text-base">home</span>
                        Indoor
                    </button>
                    <button type="button" data-room="Outdoor"
                        class="room-btn flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-sm font-medium border transition-colors bg-white dark:bg-card-dark text-gray-700 dark:text-gray-300 border-gray-200 dark:border-white/10">
                        <span class="material-symbols-outlined text-base">deck</span>
                        Outdoor
                    </button>
                </div>
            </div> --}}

            {{-- <input id="id_transaksi" type="text" value="{{ $id_transaksi }}" hidden> --}}

            <div class="flex items-center justify-between font-semibold">
                <span>Total</span>
                <span id="cartTotal">Rp0</span>
            </div>
            <button id="checkoutBtn" type="button"
                class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Checkout
            </button>
        </div>
    </aside>

    {{-- ============ FORM TERSEMBUNYI UNTUK CHECKOUT ============
         Route 'pilihTempatDuduk' melakukan redirect()->route('checkout'),
         jadi ini harus submit form biasa (full page navigation),
         BUKAN fetch/AJAX — supaya browser otomatis mengikuti redirect
         dan menampilkan halaman checkout seperti request form normal. --}}
    <form id="checkoutForm" method="POST" action="{{ route('tambahanPesanan', ['id' => $id_transaksi]) }}" class="hidden">
        @csrf
        <input type="hidden" name="ruangan" id="inputRuangan">
        <input type="hidden" name="items" id="inputItems">
    </form>

    {{-- ============ TOMBOL FLOATING KERANJANG (khusus mobile) ============ --}}
    <button id="mobileCartToggle" type="button"
        class="md:hidden fixed bottom-5 right-5 z-30 w-14 h-14 rounded-full bg-primary text-white shadow-xl flex items-center justify-center"
        aria-label="Buka keranjang">
        <span class="material-symbols-outlined text-2xl">shopping_cart</span>
        <span id="mobileCartCount"
            class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-warning text-white text-[11px] font-semibold flex items-center justify-center">0</span>
    </button>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection

@push('extra-scripts')
    <script>
        // ================= FILTER KATEGORI =================
        const filterButtons = document.querySelectorAll('.filter-btn');
        const menuCards = document.querySelectorAll('.menu-card');

        filterButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                filterButtons.forEach((b) => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-white', 'dark:bg-card-dark', 'text-gray-700',
                        'dark:text-gray-300', 'border', 'border-gray-200',
                        'dark:border-white/10');
                });
                btn.classList.add('bg-primary', 'text-white');
                btn.classList.remove('bg-white', 'dark:bg-card-dark', 'border', 'border-gray-200',
                    'dark:border-white/10');

                const filter = btn.dataset.filter;
                menuCards.forEach((card) => {
                    const show = filter === 'all' || card.dataset.category === filter;
                    card.style.display = show ? '' : 'none';
                });
            });
        });

        // ================= KERANJANG (CART) =================
        // let cart = [];
        let cart = @json($cart);


        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        const cartToggle = document.getElementById('cartToggle');
        const mobileCartToggle = document.getElementById('mobileCartToggle');
        const cartClose = document.getElementById('cartClose');
        const cartItemsEl = document.getElementById('cartItems');
        const cartEmptyMessage = document.getElementById('cartEmptyMessage');
        const cartTotalEl = document.getElementById('cartTotal');
        const cartCountEl = document.getElementById('cartCount');
        const mobileCartCountEl = document.getElementById('mobileCartCount');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const roomButtons = document.querySelectorAll('.room-btn');
        let selectedRoom = 'Indoor'; // default sesuai tombol yang aktif di awal

        roomButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                selectedRoom = btn.dataset.room;
                roomButtons.forEach((b) => {
                    const active = b.dataset.room === selectedRoom;
                    b.classList.toggle('bg-primary', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('border-primary', active);
                    b.classList.toggle('bg-white', !active);
                    b.classList.toggle('dark:bg-card-dark', !active);
                    b.classList.toggle('text-gray-700', !active);
                    b.classList.toggle('dark:text-gray-300', !active);
                    b.classList.toggle('border-gray-200', !active);
                    b.classList.toggle('dark:border-white/10', !active);
                });
            });
        });

        function formatRupiah(num) {
            return 'Rp' + num.toLocaleString('id-ID');
        }

        function openCart() {
            cartSidebar.classList.remove('translate-x-full');
            cartOverlay.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeCart() {
            cartSidebar.classList.add('translate-x-full');
            cartOverlay.classList.add('opacity-0', 'pointer-events-none');
        }

        cartToggle.addEventListener('click', openCart);
        mobileCartToggle.addEventListener('click', openCart);
        cartClose.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeCart();
        });

        function addToCart(id, name, price, stok) {
            const existing = cart.find((item) => item.id === id);
            if (existing) {
                if (existing.qty >= stok) return;
                existing.qty += 1;
            } else {
                if (stok <= 0) return;
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1,
                    stok,
                    note: ''
                });
            }
            renderCart();
            openCart();
        }

        function updateNote(id, note) {
            const item = cart.find((i) => i.id === id);
            if (!item) return;
            item.note = note;
        }

        function changeQty(id, delta) {
            const item = cart.find((i) => i.id === id);
            if (!item) return;
            if (delta > 0 && item.qty >= item.stok) return;
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter((i) => i.id !== id);
            }
            renderCart();
        }

        function removeItem(id) {
            cart = cart.filter((i) => i.id !== id);
            renderCart();
        }

        function renderCart() {
            cartItemsEl.querySelectorAll('.cart-row').forEach((el) => el.remove());

            if (cart.length === 0) {
                cartEmptyMessage.classList.remove('hidden');
            } else {
                cartEmptyMessage.classList.add('hidden');
            }

            let total = 0;
            let totalQty = 0;

            cart.forEach((item) => {
                total += item.price * item.qty;
                totalQty += item.qty;

                const row = document.createElement('div');
                row.className =
                    'cart-row bg-white dark:bg-card-dark rounded-lg p-3 border border-gray-200 dark:border-white/5 flex flex-col gap-2';
                row.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <p class="font-medium text-sm">${item.name}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${formatRupiah(item.price)}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="qty-btn w-7 h-7 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center" data-action="dec" data-id="${item.id}">
                            <span class="material-symbols-outlined text-sm">remove</span>
                        </button>
                        <span class="w-5 text-center text-sm font-medium">${item.qty}</span>
                        <button class="qty-btn w-7 h-7 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center" data-action="inc" data-id="${item.id}">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                    </div>
                    <button class="remove-btn w-7 h-7 rounded-full flex items-center justify-center text-warning" data-id="${item.id}">
                        <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                </div>
                <textarea
                    class="note-input w-full text-xs rounded-md border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-200 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-primary resize-none"
                    rows="2"
                    maxlength="150"
                    placeholder="Catatan untuk menu ini (contoh: tanpa gula, extra pedas...)"
                    data-id="${item.id}"
                >${item.note ?? ''}</textarea>
            `;
                cartItemsEl.appendChild(row);
            });

            cartTotalEl.textContent = formatRupiah(total);
            cartCountEl.textContent = totalQty;
            mobileCartCountEl.textContent = totalQty;
        }

        document.getElementById('menuGrid').addEventListener('click', (e) => {
            const btn = e.target.closest('.add-to-cart-btn');
            if (!btn || btn.disabled) return;
            addToCart(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), Number(btn.dataset.stok));
        });

        cartItemsEl.addEventListener('click', (e) => {
            const qtyBtn = e.target.closest('.qty-btn');
            const removeBtn = e.target.closest('.remove-btn');
            if (qtyBtn) {
                const delta = qtyBtn.dataset.action === 'inc' ? 1 : -1;
                changeQty(qtyBtn.dataset.id, delta);
            }
            if (removeBtn) {
                removeItem(removeBtn.dataset.id);
            }
        });

        // Catatan per menu — tidak memicu renderCart() supaya fokus & kursor textarea tidak hilang saat mengetik
        cartItemsEl.addEventListener('input', (e) => {
            const noteInput = e.target.closest('.note-input');
            if (!noteInput) return;
            updateNote(noteInput.dataset.id, noteInput.value);
        });

        renderCart();

        // ================= CHECKOUT: submit form biasa (bukan fetch) =================
        // Karena controller melakukan redirect()->route('checkout'), kita submit
        // form HTML biasa supaya browser navigasi penuh & mengikuti redirect.
        const checkoutForm = document.getElementById('checkoutForm');
        const inputRuangan = document.getElementById('inputRuangan');
        const inputItems = document.getElementById('inputItems');
        // const id_transaksi = document.getElementById('id_transaksi');

        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Keranjang masih kosong.');
                return;
            }

            const items = cart.map((item) => ({
                id: item.id,
                nama: item.name,
                qty: item.qty,
                harga: item.price,
                catatan: item.note || null,
            }));

            // id_transaksi.value = id_transaksi.value
            inputRuangan.value = selectedRoom;
            inputItems.value = JSON.stringify(items);

            checkoutForm.submit();
        });
    </script>
@endpush
