<!DOCTYPE html>
<html>

<head>
    <title>User - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Playfair+Display:wght@700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/user_home.css') }}">
   
    @stack('styles')

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#513B2E", // A rich, dark coffee brown
                        "background-light": "#F5F0E8", // A creamy off-white, like latte foam
                        "background-dark": "#1F1A17", // A very dark brown, like dark roast beans
                        "card-light": "#FFFFFF",
                        "card-dark": "#2C2521",
                        "text-light": "#513B2E",
                        "text-dark": "#DCD0C0",
                        "text-muted-light": "#755F4D",
                        "text-muted-dark": "#A08C78"
                    },
                    fontFamily: {
                        display: ["Playfair Display", "serif"],
                        sans: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                        lg: "1rem", // 16px
                        xl: "1.5rem" // 24px
                    },
                },
            },
        };
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-sans text-text-light dark:text-text-dark transition-colors duration-300">
    <div class="flex flex-col min-h-screen" id="root">
        <header class="bg-card-light/80 dark:bg-card-dark/80 backdrop-blur-sm sticky top-0 z-50 shadow-md">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                <a class="font-display text-2xl font-bold text-primary" href="#">Kopi Senja</a>

                <div class="hidden md:flex items-center space-x-8">
                    {{-- <a class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-white transition-colors duration-300 font-medium"href="{{ route('app') }}">Home</a> --}}

                    <!-- Dropdown Menu -->
                    <div class="relative group">
                        <button
                            class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-white transition-colors duration-300 font-medium flex items-center gap-1">
                            Menu
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </button>

                        <!-- Dropdown Content -->
                        <div
                            class="absolute left-0 mt-2 w-40 bg-white dark:bg-card-dark shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">

                            <a href="{{ route('menu-makanan') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white">Makanan</a>

                            <a href="{{ route('menu-minuman') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white">Minuman</a>
                        </div>
                    </div>
                </div>


                <div class="flex items-center space-x-4">
                    {{-- Tampil jika pengguna sudah login --}}
                    @if($id_pelanggan === null)
                        <p>hahai</p>
                    
                    @endif
                    
                    <a href="{{ route('login') }}" style="font-size: 0.8rem;">Login</a>
                    <a href="#" style="font-size: 0.8rem;">Sign-up</a>

                    <form action="" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            Logout
                        </button>
                    </form>


                    <button aria-label="shopping cart"
                        class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined">shopping_cart</span>
                    </button>

                    <button aria-label="mobile menu" class="md:hidden text-text-light dark:text-text-dark">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
            </nav>
        </header>


        {{-- Load Content --}}
        <div class="content">
            @yield('content')
        </div>

        <footer class="bg-card-light dark:bg-card-dark pt-12 pb-6">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="md:col-span-1">
                        <h4 class="font-display text-xl font-bold text-primary mb-4">Kopi Senja</h4>
                        <p class="text-text-muted-light dark:text-text-muted-dark text-sm mb-4">Cafe adalah tempat
                            bersantai untuk semua kalangan dari anak muda sampai di kalangan orang tua juga.</p>
                        <div class="flex space-x-4">
                            <a aria-label="Facebook"
                                class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-white transition-colors duration-300"
                                href="#"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z">
                                    </path>
                                </svg></a>
                            <a aria-label="Instagram"
                                class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-white transition-colors duration-300"
                                href="#"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.057 1.644-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z">
                                    </path>
                                </svg></a>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-display text-xl font-bold text-primary mb-4">Hubungi Kami</h4>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-primary mr-3">call</span>
                                <span class="text-text-muted-light dark:text-text-muted-dark">+62-812-1234-1234</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-primary mr-3">call</span>
                                <span class="text-text-muted-light dark:text-text-muted-dark">+62-812-1234-1234</span>
                            </li>
                            <li class="flex items-start">
                                <span class="material-symbols-outlined text-primary mr-3 mt-1">location_on</span>
                                <span class="text-text-muted-light dark:text-text-muted-dark">Jl. Nasional 1 Patrol,
                                    Kec. Patrol, Kab. Indramayu, Jawa Barat 45257</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-display text-xl font-bold text-primary mb-4">Lokasi</h4>

                        <div class="rounded-lg overflow-hidden shadow-lg aspect-video">
                            <iframe allowfullscreen="" height="100%" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.8398418536863!2d107.96222481477006!3d-6.414444995358054!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb5c1979b90c3%3A0x6b4c6e917d057a6e!2sPatrol%2C%20Indramayu%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1620896081465!5m2!1sen!2sid"
                                style="border:0;" width="100%"></iframe>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center text-sm text-text-muted-light dark:text-text-muted-dark">
                    <p>Hak Cipta © Copyright 2026. Kopi Satu Bang @HoodHGL.</p>
                </div>
            </div>
        </footer>
    </div>
    {{-- Tempat semua script dikumpulkan --}}
    @stack('extra-scripts')
    </div>
    @include('sweetalert::alert')
</body>

</html>
