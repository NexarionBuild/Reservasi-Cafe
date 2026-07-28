@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="mb-10 text-center text-2xl font-bold md:text-3xl">
            Keranjang
        </h1>

        @if (count($keranjang) == 0)
            <div class="text-center text-gray-500">
                Keranjang kosong.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                @foreach ($keranjang as $index => $item)
                    <div class="rounded-2xl bg-white p-5 shadow-md">
                        <div class="flex gap-4">
                            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}"
                                class="h-24 w-24 rounded-xl object-cover">

                            <div class="flex flex-1 flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-3">
                                        {{-- Sisi Kiri: Detail Informasi Menu --}}
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $item['nama'] }}</h3>
                                            <p class="text-sm capitalize text-gray-500">{{ $item['kategori'] ?? 'menu' }}
                                            </p>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Rp {{ number_format($item['harga']) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm capitalize text-gray-500">{{ $item['catatan'] }}
                                            </p>
                                        </div>


                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <input type="number" min="1" max="{{ $item['stok'] }}"
                                        value="{{ $item['qty'] }}" data-index="{{ $index }}"
                                        data-stok="{{ $item['stok'] }}"
                                        class="qty-input w-16 rounded-md border text-center">

                                    <form action="{{ route('remove-item', $index) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm font-medium text-red-500" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                class="mt-10 flex flex-col items-center justify-between gap-4 rounded-xl bg-white p-5 shadow-md md:flex-row">
                <a href="{{ route('reservasi') }}" class="rounded-lg bg-primary px-6 py-3 text-white">
                    Reservasi
                </a>
            </div>
        @endif
    </div>
@endsection

@push('extra-scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function updateCartItem(index, payload = {}) {
            return fetch(`/keranjang/update/${index}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            }).then(response => response.json());
        }

        function setDrinkButtonState(index, option, value) {
            document.querySelectorAll(
                `.drink-option-btn[data-index="${index}"][data-option="${option}"]`
            ).forEach(button => {
                const isActive = button.dataset.value === value;
                button.classList.toggle('border-amber-500', isActive);
                button.classList.toggle('bg-white', isActive);
                button.classList.toggle('text-slate-900', isActive);
                button.classList.toggle('shadow-sm', isActive);
                button.classList.toggle('border-transparent', !isActive);
                button.classList.toggle('bg-slate-50', !isActive);
                button.classList.toggle('text-slate-400', !isActive);
            });
        }

        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                let qty = parseInt(this.value);
                let stok = parseInt(this.dataset.stok);

                if (!qty || qty < 1) {
                    this.value = 1;
                    qty = 1;
                }

                if (qty > stok) {
                    alert('Stok hanya tersisa ' + stok);
                    this.value = stok;
                    qty = stok;
                }

                updateCartItem(this.dataset.index, {
                    qty: qty
                }).then(data => {
                    if (data.qty) {
                        this.value = data.qty;
                    }
                });
            });
        });

        document.querySelectorAll('.drink-option-btn').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.dataset.index;
                const option = this.dataset.option;
                const value = this.dataset.value;
                const qtyInput = document.querySelector(`.qty-input[data-index="${index}"]`);
                const payload = {
                    qty: qtyInput ? parseInt(qtyInput.value || '1', 10) : 1,
                    [option]: value
                };

                updateCartItem(index, payload).then(data => {
                    if (data.success) {
                        setDrinkButtonState(index, option, value);
                    }
                });
            });
        });
    </script>
@endpush
