<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Registrasi Akun Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&amp;family=Poppins:wght@400;500&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#745C52",
                        "background-light": "#F5EFE6",
                        "background-dark": "#2C2A29",
                    },
                    fontFamily: {
                        display: ["Lora", "serif"],
                        sans: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem", // 12px
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-sm mx-auto">
        <div
            class="bg-white/50 dark:bg-black/20 backdrop-blur-sm p-8 rounded-2xl shadow-lg transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
            <div class="text-center mb-8">
                <h1 class="font-display text-4xl font-bold text-primary dark:text-[#F5EFE6]">Registrasi Akun</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Buat akun baru untuk memulai.</p>
            </div>
            <form action="{{ route('form-registerAdmin') }}" class="space-y-6" method="POST">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        for="nama_pengelola">Nama Admin</label>
                    <div class="mt-1">
                        <input autocomplete="nama_pengelola"
                            class="block w-full rounded-DEFAULT border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                            id="nama_pengelola" name="nama_pengelola" type="text" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        for="username">Username</label>
                    <div class="mt-1">
                        <input autocomplete="username"
                            class="block w-full rounded-DEFAULT border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                            id="username" name="username" type="text" />
                    </div>
                </div>
               
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        for="password">Password</label>
                    <div class="mt-1">
                        <input autocomplete="password"
                            class="block w-full rounded-DEFAULT border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                            id="password" name="password" type="password" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        for="password_confirmation">Konfirmasi Password</label>
                    <div class="mt-1">
                        <input autocomplete="password"
                            class="block w-full rounded-DEFAULT border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-primary focus:border-primary transition duration-150 ease-in-out"
                            id="password_confirmation" name="password_confirmation" type="password" />
                    </div>
                </div>
                <div>
                    <button
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-DEFAULT shadow-sm text-base font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-transform duration-200 transform hover:scale-105 active:scale-95"
                        type="submit">
                        Daftar
                    </button>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a class="font-medium text-primary hover:text-opacity-80" href="{{ route('login') }}">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
        <footer class="text-center mt-8">
            <p class="text-xs text-gray-500 dark:text-gray-400">Hak Cipta© 2026. Kafe Management System.</p>
        </footer>
    </div>

</body>

</html>
