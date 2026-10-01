@extends('layouts.app')

@section('title', 'Sistem Rekomendasi Laptop Mahasiswa - SPK TOPSIS')

@section('content')
<div class="py-8 sm:py-14 space-y-20">
    <!-- Hero Section -->
    <div class="text-center max-w-4xl mx-auto">
        <!-- 21st.dev Animated Pill Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-violet-950/60 via-indigo-950/60 to-cyan-950/60 border border-violet-500/30 text-zinc-200 shadow-[0_0_20px_rgba(124,58,237,0.25)] mb-6 hover:border-cyan-400/50 transition">
            <span class="flex h-2 w-2 rounded-full bg-cyan-400 animate-ping"></span>
            <span class="font-mono text-cyan-300 font-semibold">Algoritme TOPSIS & ROC</span>
            <span class="text-zinc-600">•</span>
            <span class="text-zinc-300">Rekomendasi Laptop Cerdas Mahasiswa</span>
        </div>

        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.1] mb-6">
            Temukan Laptop Ideal untuk <br class="hidden sm:inline"/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-indigo-300 to-cyan-400">
                Kuliah & Kebutuhan Anda
            </span>
        </h1>

        <p class="text-base sm:text-lg text-zinc-300 leading-relaxed max-w-2xl mx-auto mb-10">
            Sistem Pendukung Keputusan berbasis metode <strong>TOPSIS</strong> & <strong>ROC</strong> yang memadukan komputasi matematis objektif dan analisis naratif <strong>AI</strong> untuk merekomendasikan laptop terbaik sesuai anggaran Anda.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 mb-12">
            @auth
                <a href="{{ route('questionnaire') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-bold text-sm shadow-[0_0_30px_rgba(124,58,237,0.4)] hover:shadow-[0_0_40px_rgba(124,58,237,0.7)] transition active:scale-[0.98]">
                    <span>Mulai Isi Kuisioner</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-[#0e0e15]/90 border border-white/10 hover:border-violet-500/50 text-zinc-200 hover:text-white font-semibold text-sm hover:bg-white/[0.04] transition">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-bold text-sm shadow-[0_0_30px_rgba(124,58,237,0.4)] hover:shadow-[0_0_40px_rgba(124,58,237,0.7)] transition active:scale-[0.98]">
                    <span>Daftar & Mulai Konsultasi</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-[#0e0e15]/90 border border-white/10 hover:border-violet-500/50 text-zinc-200 hover:text-white font-semibold text-sm hover:bg-white/[0.04] transition">
                    Masuk Akun
                </a>
            @endauth
        </div>

        <!-- 6 Kriteria Badges -->
        <div class="flex flex-wrap items-center justify-center gap-2">
            <span class="text-xs text-zinc-400 font-mono">Kriteria Keputusan:</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-violet-950/40 text-violet-300 border border-violet-800/40">💰 C1: Harga</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-indigo-950/40 text-indigo-300 border border-indigo-800/40">⚡ C2: Performa</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-cyan-950/40 text-cyan-300 border border-cyan-800/40">🧠 C3: RAM</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-emerald-950/40 text-emerald-300 border border-emerald-800/40">💾 C4: Storage SSD</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-amber-950/40 text-amber-300 border border-amber-800/40">🔋 C5: Baterai</span>
            <span class="px-3 py-1 rounded-xl text-xs font-mono bg-pink-950/40 text-pink-300 border border-pink-800/40">🎒 C6: Portabilitas</span>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- REKOMENDASI LAPTOP TERBARU & TERBAIK (WITH IMAGES)       -->
    <!-- ======================================================== -->
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-white/10 pb-5">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-semibold bg-cyan-950/50 text-cyan-300 border border-cyan-800/60 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Katalog Pilihan Terkini
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Rekomendasi Laptop Terbaru & Terbaik
                </h2>
                <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                    Koleksi laptop dengan nilai performa komposit unggulan yang siap dihitung kecocokannya dengan TOPSIS.
                </p>
            </div>

            <!-- Kategori Tabs Switcher -->
            <div class="flex flex-wrap items-center gap-1.5 bg-[#0e0e15] p-1.5 rounded-2xl border border-white/10">
                <button type="button" onclick="filterLaptopCategory('all')" id="tab_all"
                    class="laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-sm">
                    Semua ({{ $featuredLaptops->count() }})
                </button>
                @if(isset($topGaming) && $topGaming->isNotEmpty())
                    <button type="button" onclick="filterLaptopCategory('gaming')" id="tab_gaming"
                        class="laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-medium transition text-zinc-400 hover:text-white">
                        🎮 Gaming & 3D
                    </button>
                @endif
                @if(isset($topPortable) && $topPortable->isNotEmpty())
                    <button type="button" onclick="filterLaptopCategory('portable')" id="tab_portable"
                        class="laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-medium transition text-zinc-400 hover:text-white">
                        🎒 Portabel & Baterai
                    </button>
                @endif
                @if(isset($topBudget) && $topBudget->isNotEmpty())
                    <button type="button" onclick="filterLaptopCategory('budget')" id="tab_budget"
                        class="laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-medium transition text-zinc-400 hover:text-white">
                        💰 Hemat Anggaran
                    </button>
                @endif
            </div>
        </div>

        <!-- 1. Grid Semua Laptop Unggulan -->
        <div id="cat_all" class="laptop-group grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredLaptops as $laptop)
                <div class="group relative rounded-3xl bg-[#0e0e15]/80 border border-white/10 hover:border-violet-500/50 transition-all duration-300 overflow-hidden backdrop-blur-md flex flex-col justify-between shadow-lg hover:shadow-[0_0_30px_rgba(124,58,237,0.25)] hover:-translate-y-1">
                    <!-- Image with overlay tags -->
                    <div class="relative h-48 w-full overflow-hidden bg-zinc-950">
                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500 filter brightness-95 group-hover:brightness-105"
                            loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e15] via-transparent to-black/30"></div>

                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase tracking-wider bg-black/70 backdrop-blur-md text-white border border-white/20">
                                {{ $laptop->brand }}
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono capitalize {{ $laptop->condition === 'second' ? 'bg-amber-500/80 text-zinc-950 font-bold' : 'bg-emerald-500/80 text-white font-bold' }}">
                                {{ $laptop->condition ?: 'Baru' }}
                            </span>
                        </div>

                        <!-- Performa Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-xl text-[10px] font-mono font-bold bg-violet-950/80 backdrop-blur-md text-violet-300 border border-violet-500/40">
                                <svg class="w-3 h-3 text-violet-400" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
                                <span>{{ $laptop->performa }} / 100</span>
                            </span>
                        </div>
                    </div>

                    <!-- Laptop Body Info -->
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-bold text-white group-hover:text-cyan-300 transition line-clamp-2 leading-snug">
                                {{ $laptop->name }}
                            </h3>
                            <div class="text-base font-extrabold font-mono text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-300">
                                Rp {{ number_format($laptop->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- 4 Specs Grid -->
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                            <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                <span class="text-violet-400 font-mono">RAM</span>
                                <span class="font-bold">{{ $laptop->ram_gb }} GB</span>
                            </div>
                            <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                <span class="text-indigo-400 font-mono">SSD</span>
                                <span class="font-bold">{{ $laptop->storage_gb }} GB</span>
                            </div>
                            <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                <span class="text-amber-400 font-mono">Bat</span>
                                <span class="font-bold">{{ $laptop->battery_hours }} Jam</span>
                            </div>
                            <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                <span class="text-cyan-400 font-mono">Bobot</span>
                                <span class="font-bold">{{ $laptop->weight_kg }} Kg</span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ route('questionnaire') }}"
                            class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white/[0.06] hover:bg-gradient-to-r hover:from-violet-600 hover:to-cyan-500 text-zinc-200 hover:text-white font-semibold text-xs border border-white/10 hover:border-transparent transition duration-200">
                            <span>Hitung Kecocokan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 2. Grid Gaming -->
        @if(isset($topGaming) && $topGaming->isNotEmpty())
            <div id="cat_gaming" class="laptop-group hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topGaming as $laptop)
                    <div class="group relative rounded-3xl bg-[#0e0e15]/80 border border-white/10 hover:border-violet-500/50 transition-all duration-300 overflow-hidden backdrop-blur-md flex flex-col justify-between shadow-lg hover:shadow-[0_0_30px_rgba(124,58,237,0.25)] hover:-translate-y-1">
                        <div class="relative h-48 w-full overflow-hidden bg-zinc-950">
                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 filter brightness-95" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e15] via-transparent to-black/30"></div>
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase bg-black/70 backdrop-blur-md text-white border border-white/20">
                                    {{ $laptop->brand }}
                                </span>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-violet-600 text-white">
                                    Gaming
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-xl text-[10px] font-mono font-bold bg-violet-950/80 backdrop-blur-md text-violet-300 border border-violet-500/40">
                                    ⚡ {{ $laptop->performa }} / 100
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <h3 class="text-sm font-bold text-white group-hover:text-cyan-300 transition line-clamp-2 leading-snug">
                                    {{ $laptop->name }}
                                </h3>
                                <div class="text-base font-extrabold font-mono text-emerald-400">
                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-violet-400 font-mono">RAM</span>
                                    <span class="font-bold">{{ $laptop->ram_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-indigo-400 font-mono">SSD</span>
                                    <span class="font-bold">{{ $laptop->storage_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-amber-400 font-mono">Bat</span>
                                    <span class="font-bold">{{ $laptop->battery_hours }} Jam</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-cyan-400 font-mono">Bobot</span>
                                    <span class="font-bold">{{ $laptop->weight_kg }} Kg</span>
                                </div>
                            </div>
                            <a href="{{ route('questionnaire') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white/[0.06] hover:bg-gradient-to-r hover:from-violet-600 hover:to-cyan-500 text-zinc-200 hover:text-white font-semibold text-xs border border-white/10 transition">
                                <span>Hitung TOPSIS</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- 3. Grid Portabel -->
        @if(isset($topPortable) && $topPortable->isNotEmpty())
            <div id="cat_portable" class="laptop-group hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topPortable as $laptop)
                    <div class="group relative rounded-3xl bg-[#0e0e15]/80 border border-white/10 hover:border-cyan-500/50 transition-all duration-300 overflow-hidden backdrop-blur-md flex flex-col justify-between shadow-lg hover:shadow-[0_0_30px_rgba(6,182,212,0.25)] hover:-translate-y-1">
                        <div class="relative h-48 w-full overflow-hidden bg-zinc-950">
                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 filter brightness-95" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e15] via-transparent to-black/30"></div>
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase bg-black/70 backdrop-blur-md text-white border border-white/20">
                                    {{ $laptop->brand }}
                                </span>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-cyan-600 text-white">
                                    Portabel
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-xl text-[10px] font-mono font-bold bg-cyan-950/80 backdrop-blur-md text-cyan-300 border border-cyan-500/40">
                                    🪶 {{ $laptop->weight_kg }} Kg
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <h3 class="text-sm font-bold text-white group-hover:text-cyan-300 transition line-clamp-2 leading-snug">
                                    {{ $laptop->name }}
                                </h3>
                                <div class="text-base font-extrabold font-mono text-emerald-400">
                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-violet-400 font-mono">RAM</span>
                                    <span class="font-bold">{{ $laptop->ram_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-indigo-400 font-mono">SSD</span>
                                    <span class="font-bold">{{ $laptop->storage_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-amber-400 font-mono">Bat</span>
                                    <span class="font-bold">{{ $laptop->battery_hours }} Jam</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-cyan-400 font-mono">Bobot</span>
                                    <span class="font-bold">{{ $laptop->weight_kg }} Kg</span>
                                </div>
                            </div>
                            <a href="{{ route('questionnaire') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white/[0.06] hover:bg-gradient-to-r hover:from-violet-600 hover:to-cyan-500 text-zinc-200 hover:text-white font-semibold text-xs border border-white/10 transition">
                                <span>Hitung TOPSIS</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- 4. Grid Budget Hemat -->
        @if(isset($topBudget) && $topBudget->isNotEmpty())
            <div id="cat_budget" class="laptop-group hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topBudget as $laptop)
                    <div class="group relative rounded-3xl bg-[#0e0e15]/80 border border-white/10 hover:border-emerald-500/50 transition-all duration-300 overflow-hidden backdrop-blur-md flex flex-col justify-between shadow-lg hover:shadow-[0_0_30px_rgba(16,185,129,0.25)] hover:-translate-y-1">
                        <div class="relative h-48 w-full overflow-hidden bg-zinc-950">
                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 filter brightness-95" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e15] via-transparent to-black/30"></div>
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase bg-black/70 backdrop-blur-md text-white border border-white/20">
                                    {{ $laptop->brand }}
                                </span>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-emerald-600 text-white">
                                    Hemat
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-xl text-[10px] font-mono font-bold bg-emerald-950/80 backdrop-blur-md text-emerald-300 border border-emerald-500/40">
                                    💰 Hemat
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <h3 class="text-sm font-bold text-white group-hover:text-cyan-300 transition line-clamp-2 leading-snug">
                                    {{ $laptop->name }}
                                </h3>
                                <div class="text-base font-extrabold font-mono text-emerald-400">
                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-violet-400 font-mono">RAM</span>
                                    <span class="font-bold">{{ $laptop->ram_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-indigo-400 font-mono">SSD</span>
                                    <span class="font-bold">{{ $laptop->storage_gb }} GB</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-amber-400 font-mono">Bat</span>
                                    <span class="font-bold">{{ $laptop->battery_hours }} Jam</span>
                                </div>
                                <div class="p-2 rounded-xl bg-zinc-950/70 border border-white/5 flex items-center gap-1.5 text-zinc-300">
                                    <span class="text-cyan-400 font-mono">Bobot</span>
                                    <span class="font-bold">{{ $laptop->weight_kg }} Kg</span>
                                </div>
                            </div>
                            <a href="{{ route('questionnaire') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white/[0.06] hover:bg-gradient-to-r hover:from-violet-600 hover:to-cyan-500 text-zinc-200 hover:text-white font-semibold text-xs border border-white/10 transition">
                                <span>Hitung TOPSIS</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 21st.dev Component Feature Bento Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Step 1 -->
        <div class="relative group p-7 rounded-3xl bg-[#0e0e15]/70 border border-white/10 hover:border-violet-500/50 transition-all duration-300 backdrop-blur-md hover:shadow-[0_0_25px_rgba(124,58,237,0.2)]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-600/30 to-indigo-600/30 border border-violet-500/30 flex items-center justify-center text-violet-300 mb-5 font-mono font-bold text-sm shadow-[0_0_15px_rgba(124,58,237,0.3)]">
                01
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Preferensi Mahasiswa</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Tentukan rentang anggaran riil, peruntukan studi (Coding, Desain, Gaming, Kuliah), kapasitas RAM & SSD, serta mobilitas harian tanpa asumsi kaku.
            </p>
        </div>

        <!-- Step 2 -->
        <div class="relative group p-7 rounded-3xl bg-[#0e0e15]/70 border border-white/10 hover:border-indigo-500/50 transition-all duration-300 backdrop-blur-md hover:shadow-[0_0_25px_rgba(99,102,241,0.2)]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600/30 to-cyan-600/30 border border-indigo-500/30 flex items-center justify-center text-cyan-300 mb-5 font-mono font-bold text-sm shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                02
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Pembobotan & TOPSIS</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Matriks keputusan ternormalisasi terbobot dihitung secara objektif. Jarak Euclidean ke solusi ideal positif ($A^+$) dan negatif ($A^-$) menghasilkan ranking presisi.
            </p>
        </div>

        <!-- Step 3 -->
        <div class="relative group p-7 rounded-3xl bg-[#0e0e15]/70 border border-white/10 hover:border-cyan-500/50 transition-all duration-300 backdrop-blur-md hover:shadow-[0_0_25px_rgba(6,182,212,0.2)]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-600/30 to-emerald-600/30 border border-cyan-500/30 flex items-center justify-center text-emerald-300 mb-5 font-mono font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                03
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Penjelasan AI & Unduh PDF</h3>
            <p class="text-sm text-zinc-400 leading-relaxed">
                Setiap alternatif teratas dilengkapi ulasan naratif cerdas bertenaga AI mengapa laptop tersebut tepat untuk kebutuhan Anda, lengkap dengan cetak laporan PDF.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterLaptopCategory(cat) {
        // Reset buttons
        document.querySelectorAll('.laptop-tab-btn').forEach(btn => {
            btn.className = 'laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-medium transition text-zinc-400 hover:text-white';
        });

        const activeBtn = document.getElementById('tab_' + cat);
        if (activeBtn) {
            activeBtn.className = 'laptop-tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-sm';
        }

        // Hide all groups
        document.querySelectorAll('.laptop-group').forEach(group => {
            group.classList.add('hidden');
        });

        // Show target group
        const target = document.getElementById('cat_' + cat);
        if (target) {
            target.classList.remove('hidden');
        }
    }
</script>
@endpush
@endsection
