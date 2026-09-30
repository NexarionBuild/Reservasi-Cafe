@extends('layouts.admin')

@section('title', 'Tambah Meja')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/form-makanan.css') }}">
@endpush

@section('content')
<div class="flex justify-center px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl bg-white dark:bg-surface-dark rounded-xl shadow-lg p-6 sm:p-8">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- TITLE --}}
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-primary mb-8">
            Tambah Meja
        </h1>

        {{-- FORM --}}
        <form action="{{ route('dataMeja.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- FORM GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- KODE MEJA --}}
                <div>
                    <label for="kode_meja" class="block text-sm font-medium mb-1 text-gray-700">
                        Kode Meja
                    </label>
                    <input type="text" name="kode_meja" id="kode_meja"
                        placeholder="Kode Meja"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2
                               focus:ring-primary focus:border-primary transition text-black">
                </div>

                {{-- RUANGAN --}}
                <div>
                    <label for="ruangan" class="block text-sm font-medium mb-1 text-gray-700">
                        Ruangan
                    </label>
                    <select name="ruangan" id="ruangan"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2
                               focus:ring-primary focus:border-primary transition text-gray-700">
                        <option value="Indoor">Indoor</option>
                        {{-- <option value="Indoor">Indoor</option> --}}
                        <option value="Outdoor">Outdoor</option>
                    </select>
                </div>

            {{-- SUBMIT --}}
            <div class="mt-8">
                <button type="submit"
                    class="w-full bg-primary text-white font-semibold py-3 rounded-lg
                           hover:bg-primary/90 transition shadow-md hover:shadow-lg">
                    Selanjutnya
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('extra-script')
<script>
document.getElementById('previewGambar').addEventListener('change', function(e) {
    const img = document.getElementById('gambarPreview');
    const file = e.target.files[0];

    if (file) {
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
    }
});
</script>
@endpush
