<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin-Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6F4E37", // Coffee brown
                        accent: "#C8A97E", // Latte / caramel
                        "background-light": "#F5F0E8", // Cream
                        "background-dark": "#1F1A17", // Espresso
                        "background-aside": "#3E2C23", // Dark wood
                        "card-dark": "#2C2521", // Dark coffee
                        muted: "#9C8B7E", // Warm gray
                        success: "#7A8F5B", // Olive
                        warning: "#C27C3A", // Cinnamon
                    },
                    fontFamily: {
                        display: ["Work Sans", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "0.75rem",
                        xl: "1rem",
                        full: "9999px"
                    }
                }
            }
        }
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-light text-gray-900 dark:text-white font-display overflow-hidden antialiased">
    <div class="flex h-screen w-full">

        <!-- OVERLAY -->
        <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="closeSidebar()"></div>

        <!-- SIDEBAR MOBILE -->
        <aside id="mobileSidebar"
            class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-background-aside
                transform -translate-x-full transition-transform duration-300
                z-40 p-4 flex flex-col justify-between md:hidden">

            <div class="flex flex-col gap-6">

                <!-- Branding -->
                <div class="px-2 flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-background-dark">lunch_dining</span>
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}">
                            {{-- <img src="{{ asset('images/logo.png') }}" class="h-10"> --}}
                            <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white leading-none">
                                Resto<span class="text-primary">Admin</span></h1>
                            <p
                                class="text-[10px] uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mt-1">
                                Dashboard</p>
                        </a>
                    </div>
                </div>

                <!-- Menu Items -->
                <nav class="flex flex-col gap-1 ">
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined fill-1">home</span>
                        Home
                    </a>
                    {{-- <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('admin.transaksiLangsung') }}">
                        <span class="material-symbols-outlined">payments</span>
                        Kasir
                    </a> --}}
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('showMakanan') }}">
                        <span class="material-symbols-outlined">restaurant</span>
                        Makanan
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('showMinuman') }}">
                        <span class="material-symbols-outlined">local_cafe</span>
                        Minuman
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('dataUser') }}">
                        <span class="material-symbols-outlined">group</span>
                        Data User
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('reservasiData') }}">
                        <span class="material-symbols-outlined">history</span>
                        Data Reservasi
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('dataMeja.showMeja') }}">
                        <span class="material-symbols-outlined">table_restaurant</span>
                        Data Meja
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('setting') }}">
                        <span class="material-symbols-outlined">settings</span>
                        Setting
                    </a>
                </nav>
            </div>

            <!-- LOGOUT -->
            
            <a href="{{ route('logout-admin') }}" class="mt-6 flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-primary/90 text-white px-4 py-3 font-bold">
                <span class="material-symbols-outlined">logout</span>
                Keluar
            </a>
        </aside>


        <!-- Sidebar Navigation -->
        <aside
            class="hidden md:flex w-64 flex-col justify-between border-r border-gray-200 dark:border-white/5 bg-white dark:bg-background-aside p-4 z-20">
            <div class="flex flex-col gap-6">

                <!-- Branding -->
                <div class="px-2 flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-background-dark">lunch_dining</span>
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}">
                            {{-- <img src="{{ asset('images/logo.png') }}" class="h-10"> --}}
                            <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white leading-none">
                                Resto<span class="text-primary">Admin</span></h1>
                            <p
                                class="text-[10px] uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mt-1">
                                Dashboard</p>
                        </a>
                    </div>
                </div>

                <!-- Menu Items -->
                <nav class="flex flex-col gap-1 ">
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined fill-1">home</span>
                        Home
                    </a>
                    {{-- <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('admin.transaksiLangsung') }}">
                        <span class="material-symbols-outlined">payments</span>
                        Kasir
                    </a> --}}
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('showMakanan') }}">
                        <span class="material-symbols-outlined">restaurant</span>
                        Makanan
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('showMinuman') }}">
                        <span class="material-symbols-outlined">local_cafe</span>
                        Minuman
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('dataUser') }}">
                        <span class="material-symbols-outlined">group</span>
                        Data User
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('reservasiData') }}">
                        <span class="material-symbols-outlined">history</span>
                        Data Reservasi
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('dataMeja.showMeja') }}">
                        <span class="material-symbols-outlined">table_restaurant</span>
                        Data Meja
                    </a>
                    <a class="group flex items-center gap-3 px-3 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all font-medium"
                        href="{{ route('setting') }}">
                        <span class="material-symbols-outlined">settings</span>
                        Setting
                    </a>
                </nav>
            </div>

            <!-- Logout Button -->
            <a href="{{ route('logout-admin') }}" class="mt-6 flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-primary/90 text-white px-4 py-3 font-bold">
                <span class="material-symbols-outlined">logout</span>
                Keluar
            </a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-full overflow-y-auto bg-background-light dark:bg-background-light relative">

            <!-- Header -->
            <header
                class="sticky top-0 z-10 flex items-center justify-between px-8 py-2 bg-background-light/80 dark:bg-background-aside/80 backdrop-blur-md border-b border-gray-200 dark:border-white/5">
                <!-- HAMBURGER (MOBILE) -->
                <button id="btnSidebar"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 transition">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Admin Panel</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here is what's happening today.</p>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        class="relative p-2 rounded-full hover:bg-gray-200 dark:hover:bg-white/10 text-gray-500 dark:text-gray-400 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span
                            class="absolute top-2 right-2 h-2 w-2 rounded-full bg-primary border-2 border-background-light dark:border-background-dark"></span>
                    </button>
                    <div
                        class="h-10 w-10 rounded-full border-2 border-white dark:border-white/10 overflow-hidden shadow-sm">
                        <img alt="User Profile" class="h-full w-full object-cover" data-alt="Portrait of an admin user"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDL0bKFqKnFphpjUjWK4fEdqoqlLEuBDPPbSrMSnu3Bir8rKJTwYEttt_cQVbYEIg22kAY_eN9K7YytKrgAOVEhXX2HfgmfElbfJAZK16Cte3cP8OaoOsyBY8Bme3uZYsdlnLIs8q9wRBj1WMEJItCgK9mvGTXmWhqjHsa4ZLfye7j4TaTf1Y3JTvmipP-TJBeAedIrm5ollD3dFyNW-VScFRnpGs437c0qOnTuKOS_YnRBS8xRYw6IYscBa4LJ9LiOBDicmF8_MeOP" />
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="px-8 pb-12 flex flex-col gap-8 pt-6">
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('extra-scripts')
    <script>
        const btnSidebar = document.getElementById('btnSidebar');
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('overlay');

        btnSidebar?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    </script>
    
</body>

</html>
