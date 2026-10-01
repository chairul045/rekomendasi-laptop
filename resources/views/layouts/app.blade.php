<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Rekomendasi Laptop - SPK TOPSIS')</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #09090d;
            color: #f4f4f5;
        }
        .code-font {
            font-family: 'JetBrains Mono', monospace;
        }
        /* 21st.dev grid & radial backdrop */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 3rem 3rem;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 10%, #000 70%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 10%, #000 70%, transparent 100%);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #09090d;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }
        /* 21st.dev glow utilities */
        .glow-border {
            position: relative;
        }
        .glow-border::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(to bottom right, rgba(168, 85, 247, 0.4), rgba(59, 130, 246, 0.2), rgba(6, 182, 212, 0.4));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-[#09090d] text-zinc-100 antialiased selection:bg-violet-500 selection:text-white">
    <!-- Ambient 21st.dev colorful glow backdrops -->
    <div class="fixed inset-0 pointer-events-none bg-grid-pattern opacity-80 z-0"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[850px] h-[400px] bg-gradient-to-b from-violet-600/15 via-indigo-600/10 to-transparent blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed top-20 right-0 w-[500px] h-[350px] bg-gradient-to-bl from-cyan-500/15 via-blue-600/10 to-transparent blur-[110px] pointer-events-none z-0"></div>
    <div class="fixed top-60 left-0 w-[450px] h-[350px] bg-gradient-to-tr from-pink-500/10 via-purple-600/10 to-transparent blur-[110px] pointer-events-none z-0"></div>

    <!-- 21st.dev style glass navbar with colorful accents -->
    <header class="sticky top-0 z-40 w-full border-b border-white/[0.08] bg-[#09090d]/80 backdrop-blur-xl transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 via-indigo-600 to-cyan-500 p-0.5 shadow-[0_0_20px_rgba(124,58,237,0.35)] group-hover:shadow-[0_0_30px_rgba(124,58,237,0.6)] transition duration-300">
                        <div class="w-full h-full bg-[#09090d] rounded-[10px] flex items-center justify-center text-white">
                            <svg class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2"/>
                                <line x1="8" y1="21" x2="16" y2="21" stroke-width="2" stroke-linecap="round"/>
                                <line x1="12" y1="17" x2="12" y2="21" stroke-width="2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm tracking-tight text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-white group-hover:via-violet-200 group-hover:to-cyan-300 transition">LaptopSPK</span>
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-mono font-medium bg-gradient-to-r from-violet-500/20 to-cyan-500/20 text-cyan-300 border border-cyan-500/30">TOPSIS</span>
                        </div>
                        <span class="text-[10px] text-zinc-400 hidden sm:inline">Smart Decision & AI System</span>
                    </div>
                </a>

                @auth
                    <nav class="hidden md:flex items-center gap-1.5 text-xs font-medium">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-violet-500/20 to-indigo-500/20 border border-violet-500/40 shadow-sm' : 'text-zinc-400 hover:text-zinc-100 hover:bg-white/[0.04]' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('questionnaire') }}" class="px-3 py-1.5 rounded-lg transition {{ request()->routeIs('questionnaire') ? 'text-white bg-gradient-to-r from-violet-500/20 to-indigo-500/20 border border-violet-500/40 shadow-sm' : 'text-zinc-400 hover:text-zinc-100 hover:bg-white/[0.04]' }}">
                            Kuisioner
                        </a>
                        <a href="{{ route('questionnaire.history') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('questionnaire.history') ? 'text-white bg-gradient-to-r from-violet-500/20 to-indigo-500/20 border border-violet-500/40 shadow-sm' : 'text-zinc-400 hover:text-zinc-100 hover:bg-white/[0.04]' }}">
                            <span>Riwayat Kuisioner</span>
                            @php $historyCount = Auth::user()->kuisionerJawaban()->count(); @endphp
                            @if($historyCount > 0)
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ request()->routeIs('questionnaire.history') ? 'bg-cyan-400 text-zinc-950 font-bold' : 'bg-zinc-800 text-cyan-300 border border-cyan-500/30' }}">
                                    {{ $historyCount }}
                                </span>
                            @endif
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 {{ request()->routeIs('admin.*') ? 'text-amber-300 bg-amber-950/40 border border-amber-500/50' : 'text-amber-400/90 hover:text-amber-300 hover:bg-amber-950/20' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Admin Panel</span>
                            </a>
                        @endif
                    </nav>
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <div class="hidden sm:flex items-center gap-2.5 pl-3 border-l border-white/10">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 p-[1px] shadow-sm">
                            <div class="w-full h-full rounded-full bg-zinc-900 flex items-center justify-center text-xs font-mono font-bold text-violet-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-medium text-zinc-200 truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-zinc-400 code-font capitalize flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400' : 'bg-cyan-400' }}"></span>
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-zinc-400 hover:text-rose-300 hover:bg-rose-950/30 border border-white/10 hover:border-rose-800/50 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-medium text-zinc-300 hover:text-white px-3.5 py-1.5 rounded-xl hover:bg-white/[0.06] border border-transparent hover:border-white/10 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white shadow-[0_0_20px_rgba(124,58,237,0.35)] hover:shadow-[0_0_30px_rgba(124,58,237,0.6)] transition active:scale-[0.98]">
                        <span>Daftar Akun</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Toast / Flash Notifications -->
    <div class="relative z-30 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="mb-4 flex items-center justify-between gap-3 p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-800/50 text-emerald-200 text-sm backdrop-blur-md">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xs">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 flex items-center justify-between gap-3 p-3.5 rounded-xl bg-rose-950/40 border border-rose-800/50 text-rose-200 text-sm backdrop-blur-md">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 text-xs">✕</button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-950/40 border border-rose-800/50 text-rose-200 text-sm backdrop-blur-md">
                <div class="font-medium mb-1.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Terdapat beberapa kesalahan:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Slot -->
    <main class="relative z-10 flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- 21st.dev style colorful sleek footer with No HP/WhatsApp & FB link -->
    <footer class="relative z-10 border-t border-white/[0.08] bg-[#09090d]/80 backdrop-blur-xl py-8 text-xs text-zinc-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center gap-3 text-center sm:text-left">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400 animate-pulse"></span>
                    <span class="code-font text-xs font-semibold text-zinc-200">LaptopSPK &bull; Algoritme TOPSIS & ROC</span>
                </div>
                <span class="hidden sm:inline text-zinc-600">|</span>
                <span class="text-zinc-400">Sistem Pendukung Keputusan Pemilihan Laptop Mahasiswa Berbasis AI</span>
            </div>

            <!-- Kontak No HP / WhatsApp & Facebook -->
            <div class="flex items-center gap-3">
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-emerald-950/40 hover:bg-emerald-900/50 text-emerald-300 border border-emerald-800/50 hover:border-emerald-600 transition shadow-sm">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.825 2.791.826 3.182 0 5.768-2.587 5.768-5.766-.001-3.181-2.587-5.767-5.768-5.767zm3.393 8.163c-.144.405-.837.774-1.17.824-.312.045-.694.073-2.128-.521-1.615-.669-2.716-2.316-2.799-2.427-.083-.111-.663-.882-.663-1.682s.421-1.194.571-1.357c.149-.163.328-.204.437-.204.108 0 .217.001.312.006.101.005.236-.038.37.284.144.344.492 1.197.535 1.285.043.088.072.19.014.305-.058.115-.088.187-.174.288-.087.101-.183.226-.261.304-.088.087-.18.181-.077.357.103.176.458.756.983 1.224.675.602 1.244.788 1.42.875.176.087.279.073.383-.044.103-.118.444-.517.562-.693.118-.176.236-.147.397-.088.161.059 1.02.481 1.196.569.176.088.293.132.337.206.044.073.044.425-.1.83z"/>
                    </svg>
                    <span>No WhatsApp / Bantuan</span>
                </a>

                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-blue-950/40 hover:bg-blue-900/50 text-blue-300 border border-blue-800/50 hover:border-blue-600 transition shadow-sm">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
