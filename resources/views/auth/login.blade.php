<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login Kopi Satu Bang @HoodHGL</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Roboto:wght@400;500&amp;display=swap"
        rel="stylesheet" />
    <style>

    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6B4F4B",
                        "background-light": "#F5EFE6",
                        "background-dark": "#2C2C2C",
                    },
                    fontFamily: {
                        display: ["Playfair Display", "serif"],
                        sans: ["Roboto", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-sans text-slate-800 dark:text-slate-300 antialiased transition-colors duration-300">
    @if (session('warning'))
        <div
            class="mb-4 flex items-center gap-3 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-amber-700 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.66 18h16.68a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif
    @if (session('success'))
        <div
            class="mb-4 flex items-center gap-3 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-700 shadow-sm">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3m0 4h.01M4.93 4.93l14.14 14.14M1 12C1 5.924 5.924 1 12 1s11 4.924 11 11-4.924 11-11 11S1 18.076 1 12z" />
            </svg>

            <!-- Text -->
            <span>
                {{-- <strong class="font-semibold">Berhasil</strong> --}}
                {{ session('success') }}
            </span>
        </div>
    @endif
    {{-- <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm"
        id="time-system-modal">
        <div class="w-full max-w-lg rounded-2xl border border-white/20 bg-white px-6 py-7 shadow-2xl">
            <div class="mb-5 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">
                Informasi Sistem
            </div>
            <h2 class="font-display text-3xl font-bold text-primary">Ketentuan Waktu Reservasi</h2>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Reservasi tersedia pada pukul 11:00 WIB, 12:00 WIB, 13:00 WIB, dan seterusnya hingga 22:00 WIB 
                Setiap satu jam sesuai dengan kebijakan dan jam operasional kafe.  
                Klik <strong>Lanjut</strong> untuk menggunakan akun yang telah terisi otomatis.
            </p>
            <div class="mt-5 rounded-xl bg-slate-100 px-4 py-3 text-sm text-slate-700">
                Username: <strong>budi</strong><br>
                Password: <strong>budi</strong>
            </div>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button
                    class="flex-1 rounded-xl bg-primary px-4 py-3 font-semibold text-white transition hover:bg-primary/90"
                    id="continue-login" type="button">
                    Lanjut
                </button>
                <a class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-center font-semibold text-slate-700 transition hover:bg-slate-100"
                    href="{{ route('home') }}">
                    Batal
                </a>
            </div>
        </div>
    </div> --}}

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md perspective-card">
            <div
                class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm rounded-lg shadow-2xl p-8 transform transition-all duration-500">
                <div class="text-center mb-8">
                    <h1 class="font-display text-4xl font-bold text-primary">Login Kopi Satu Bang @HoodHGL</h1>
                    <p class="text-slate-600 dark:text-slate-400 mt-2">Welcome back, please log in.</p>
                </div>
                @if (session('gagal'))
                    <div class="alert alert-danger">
                        {{-- Menampilkan nilai dari session('gagal') --}}
                        <strong>Gagal!</strong> {{ session('gagal') }}
                    </div>
                @endif
                <form action="{{ route('login-pelanggan') }}" class="space-y-6" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            for="username">Username</label>
                        <input
                            class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition duration-200"
                            id="username" name="username" required="" type="text" value="{{ old('username') }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            for="password">Password</label>
                        <input
                            class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition duration-200"
                            id="password" name="password" required="" type="password" />
                    </div>
                    <div>
                        <button
                            class="w-full bg-primary text-white font-bold py-3 px-4 rounded hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark shadow-lg hover:shadow-primary/40 transform hover:-translate-y-0.5 transition-all duration-300"
                            type="submit">Login</button>
                    </div>
                </form>
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Silakan daftar jika Anda belum memiliki akun
                        <a class="font-medium text-primary hover:text-opacity-80" href="{{ route('register') }}">
                            Masuk di sini
                        </a>
                    </p>
                </div>
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-500">Hak Cipta© 2026. Kopi Satu Bang Management System.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('time-system-modal');
        const continueButton = document.getElementById('continue-login');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        continueButton.addEventListener('click', () => {
            usernameInput.value = 'budi';
            passwordInput.value = 'budi';
            modal.classList.add('hidden');
            usernameInput.focus();
        });
    </script>
</body>

</html>
