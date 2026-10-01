@extends('layouts.app')

@section('title', 'Kuisioner Kebutuhan Laptop - SPK TOPSIS & ROC')

@section('content')
<div class="max-w-4xl mx-auto py-4">
    <!-- Header -->
    <div class="mb-8 text-center sm:text-left">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono bg-gradient-to-r from-violet-950/60 to-cyan-950/60 border border-violet-500/30 text-cyan-300 mb-3 shadow-[0_0_15px_rgba(124,58,237,0.2)]">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            <span>Kuisioner Preferensi & SPK TOPSIS</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Kuisioner Kebutuhan <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-indigo-300 to-cyan-400">Laptop Mahasiswa</span>
        </h1>
        <p class="text-sm text-zinc-300 mt-2 leading-relaxed max-w-2xl">
            Isi preferensi penggunaan Anda. Sistem akan memproses perhitungan secara otomatis, menyaring katalog alternatif laptop, dan menyajikan rekomendasi terbaik lengkap dengan analisis <strong>AI</strong>.
        </p>
    </div>

    @if($copyFrom)
        <div class="mb-6 p-4 rounded-xl bg-blue-950/40 border border-blue-800/60 flex items-center justify-between gap-4 backdrop-blur-md">
            <div class="flex items-center gap-3 text-xs text-blue-200">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                <span>Preferensi dimuat dari: <strong>{{ $copyFrom->judul_sesi }}</strong> ({{ $copyFrom->created_at->format('d M Y') }}). Anda dapat menyesuaikan pilihan lalu menyimpan sebagai riwayat baru.</span>
            </div>
            <a href="{{ route('questionnaire') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium whitespace-nowrap">
                Reset &times;
            </a>
        </div>
    @elseif($totalHistory > 0)
        <div class="mb-6 p-4 rounded-xl bg-zinc-900/60 border border-zinc-800 flex items-center justify-between gap-4 backdrop-blur-md">
            <div class="flex items-center gap-3 text-xs text-zinc-300">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Anda memiliki <strong>{{ $totalHistory }} riwayat kuisioner</strong> tersimpan. Setiap pengisian baru akan tersimpan otomatis sebagai riwayat terpisah.</span>
            </div>
            <a href="{{ route('questionnaire.history') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium whitespace-nowrap">
                Buka Riwayat Kuisioner &rarr;
            </a>
        </div>
    @endif

    <form method="POST" action="{{ route('questionnaire.store') }}" class="space-y-8" id="questionnaireForm">
        @csrf

        <!-- 21st.dev Session Header: Label & Tanggal Pengisian -->
        <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md space-y-4 shadow-lg">
            <div class="grid sm:grid-cols-12 gap-4">
                <div class="sm:col-span-8 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs sm:text-sm font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                            <span>Nama / Label Kuisioner (Opsional)</span>
                        </label>
                        <span class="text-[11px] text-zinc-400 font-mono">Penanda sesi riwayat</span>
                    </div>
                    <input type="text" name="judul"
                        value="{{ old('judul', $copyFrom ? ('Salinan dari ' . $copyFrom->judul_sesi) : '') }}"
                        placeholder="Contoh: Kebutuhan Skripsi, Laptop Editing Video, atau Kuliah Harian"
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-950/90 border border-white/10 text-zinc-100 placeholder-zinc-400 text-xs sm:text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                </div>

                <div class="sm:col-span-4 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs sm:text-sm font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            <span>Tanggal Pengisian</span>
                        </label>
                        <span class="text-[11px] text-cyan-400 font-mono">WIB</span>
                    </div>
                    <div class="relative">
                        <input type="date" name="tanggal_pengisian" id="tanggal_pengisian"
                            value="{{ old('tanggal_pengisian', now()->timezone('Asia/Jakarta')->format('Y-m-d')) }}"
                            required
                            class="w-full pl-10 pr-3 py-2.5 rounded-xl bg-zinc-950/90 border border-white/10 text-zinc-100 font-mono text-xs sm:text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition cursor-pointer">
                        <svg class="w-4 h-4 text-cyan-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. Pertanyaan 1: Rentang Budget (Filter Harga) -->
        <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-mono font-bold text-zinc-300">1</span>
                    <label class="text-sm font-semibold text-white">Rentang Anggaran (Budget)</label>
                </div>
                <span class="text-[11px] font-mono text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/40">Fungsi: Filter Data</span>
            </div>
            <p class="text-xs text-zinc-400">
                Tentukan batas minimal dan maksimal harga laptop yang akan diikutsertakan dalam pemeringkatan TOPSIS.
            </p>

            <!-- Budget Presets -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @php
                    $presets = [
                        ['label' => 'Rp 3 - 6 Juta', 'min' => 3000000, 'max' => 6000000],
                        ['label' => 'Rp 6 - 10 Juta', 'min' => 6000000, 'max' => 10000000],
                        ['label' => 'Rp 10 - 15 Juta', 'min' => 10000000, 'max' => 15000000],
                        ['label' => 'Rp 15 - 30 Juta', 'min' => 15000000, 'max' => 30000000],
                    ];
                @endphp
                @foreach($presets as $p)
                    <button type="button"
                        onclick="setBudget({{ $p['min'] }}, {{ $p['max'] }})"
                        class="py-2 px-3 rounded-xl border border-zinc-800 bg-zinc-950/60 hover:border-zinc-600 text-zinc-300 text-xs font-medium transition text-center active:scale-[0.98]">
                        {{ $p['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div>
                    <label class="text-xs text-zinc-400 block mb-1">Batas Minimal (Rp)</label>
                    <input type="number" name="budget_min" id="budget_min"
                        value="{{ old('budget_min', $copyFrom->budget_min ?? 5000000) }}" step="500000" min="0" required
                        class="w-full px-3.5 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-sm focus:outline-none focus:border-zinc-500 transition">
                </div>
                <div>
                    <label class="text-xs text-zinc-400 block mb-1">Batas Maksimal (Rp)</label>
                    <input type="number" name="budget_max" id="budget_max"
                        value="{{ old('budget_max', $copyFrom->budget_max ?? 15000000) }}" step="500000" min="1000000" required
                        class="w-full px-3.5 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-sm focus:outline-none focus:border-zinc-500 transition">
                </div>
            </div>
        </div>

        <!-- 2. Pertanyaan 2: Peruntukan Pemakaian (Label Informatif) -->
        <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-mono font-bold text-zinc-300">2</span>
                    <label class="text-sm font-semibold text-white">Peruntukan Pemakaian Utama</label>
                </div>
                <span class="text-[11px] font-mono text-zinc-400 bg-zinc-800/60 px-2 py-0.5 rounded border border-zinc-700/50">Fungsi: Label Informatif</span>
            </div>
            <p class="text-xs text-zinc-400">
                Pilih aktivitas utama laptop. Pilihan ini akan ditampilkan sebagai label profil pada hasil dan memandu narasi AI.
            </p>

            @php
                $peruntukanList = [
                    'Kuliah & Skripsi' => ['Dokumen, browsing riset, presentasi, Zoom/Meet, dan tugas harian.', '🎓'],
                    'Programming & Dev' => ['Coding IDE, kompilasi software, web development, dan emulator.', '💻'],
                    'Desain & Multimedia' => ['Photoshop, Illustrator, video editing, canva, dan rendering grafis.', '🎨'],
                    'Gaming & 3D Render' => ['Gaming kompetitif/AAA, modeling 3D, Blender, dan simulasi berat.', '🎮'],
                    'Kerja Kantoran' => ['Spreadsheet kompleks, administrasi, akuntansi, dan multitasking bisnis.', '📊'],
                ];
                $currentPeruntukan = old('peruntukan', $copyFrom->peruntukan ?? 'Kuliah & Skripsi');
            @endphp
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach($peruntukanList as $val => $info)
                    <label class="relative flex flex-col p-4 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-white has-[:checked]:bg-zinc-800/70 has-[:checked]:ring-1 has-[:checked]:ring-white/20">
                        <input type="radio" name="peruntukan" value="{{ $val }}" class="sr-only" {{ $currentPeruntukan === $val ? 'checked' : '' }}>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-base">{{ $info[1] }}</span>
                            <span class="text-xs font-bold text-zinc-100">{{ $val }}</span>
                        </div>
                        <span class="text-[11px] text-zinc-400 leading-snug">{{ $info[0] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 3. Pertanyaan 3: Gaya Penggunaan & Fokus Kebutuhan (Ramah Orang Awam) -->
        <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md space-y-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center text-xs font-mono font-bold text-white shadow-sm">3</span>
                    <label class="text-sm font-bold text-white">Gaya Penggunaan & Fokus Kebutuhan Utama Anda</label>
                </div>
                <span class="text-[11px] font-mono text-emerald-400 bg-emerald-950/40 px-2.5 py-0.5 rounded-full border border-emerald-800/40">✨ Ramah Orang Awam</span>
            </div>
            <p class="text-xs text-zinc-400 leading-relaxed">
                Pilih salah satu opsi kebutuhan di bawah ini yang paling menggambarkan rencana pemakaian laptop Anda. Anda tidak perlu bingung dengan rumus atau istilah teknis—sistem otomatis menyusun urutan prioritas pembobotan (ROC) yang paling pas untuk Anda.
            </p>

            @php
                $awamPresets = [
                    'hemat' => [
                        'title' => 'Harga Paling Hemat & Ramah Kantong',
                        'icon' => '💰',
                        'badge' => 'Paling Ekonomis',
                        'desc' => 'Mencari laptop termurah yang tetap handal untuk mengetik tugas kuliah, browsing materi, Zoom, dan tugas harian.',
                        'order' => ['C1', 'C5', 'C3', 'C4', 'C6', 'C2'],
                    ],
                    'performa' => [
                        'title' => 'Kinerja Kencang & Anti-Lemot',
                        'icon' => '⚡',
                        'badge' => 'Multitasking Cepat',
                        'desc' => 'Memprioritaskan prosesor bertenaga dan RAM lapang agar tidak lelet saat membuka banyak aplikasi kuliah sekaligus.',
                        'order' => ['C2', 'C3', 'C4', 'C1', 'C5', 'C6'],
                    ],
                    'mobilitas' => [
                        'title' => 'Baterai Awet Seharian & Bobot Ringan',
                        'icon' => '🔋',
                        'badge' => 'Nyaman Dibawa',
                        'desc' => 'Memprioritaskan daya tahan baterai panjang dan laptop ringan agar praktis dibawa keliling kampus tanpa harus sering mencari colokan listrik.',
                        'order' => ['C5', 'C6', 'C1', 'C3', 'C4', 'C2'],
                    ],
                    'storage' => [
                        'title' => 'Kapasitas Penyimpanan Besar (SSD Lega)',
                        'icon' => '💾',
                        'badge' => 'Simpan Banyak File',
                        'desc' => 'Memprioritaskan ruang SSD lapang untuk menyimpan ribuan dokumen kuliah, modul, video praktikum, dan materi skripsi.',
                        'order' => ['C4', 'C3', 'C2', 'C1', 'C5', 'C6'],
                    ],
                    'kreator' => [
                        'title' => 'Olah Grafis, Desain & Editing Video',
                        'icon' => '🎨',
                        'badge' => 'Visual & Kreatif',
                        'desc' => 'Memprioritaskan performa grafis tinggi untuk aplikasi desain visual (Photoshop/Canva), render video, atau hiburan gaming lancar.',
                        'order' => ['C2', 'C4', 'C3', 'C1', 'C5', 'C6'],
                    ],
                    'seimbang' => [
                        'title' => 'Spesifikasi Seimbang di Segala Sisi',
                        'icon' => '⚖️',
                        'badge' => 'All-Rounder Terbaik',
                        'desc' => 'Kombinasi merata dan seimbang antara harga bersahabat, ketahanan baterai, performa cepat, dan kenyamanan pemakaian jangka panjang.',
                        'order' => ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'],
                    ],
                ];

                $currentRanking = old('ranking_kriteria', $copyFrom->ranking_kriteria ?? ['C1', 'C2', 'C3', 'C4', 'C5', 'C6']);

                $activePreset = 'seimbang';
                foreach ($awamPresets as $key => $preset) {
                    if ($preset['order'] === $currentRanking) {
                        $activePreset = $key;
                        break;
                    }
                }
                if ($activePreset === 'seimbang' && !empty($currentRanking[0])) {
                    if ($currentRanking[0] === 'C2') $activePreset = 'performa';
                    elseif ($currentRanking[0] === 'C5') $activePreset = 'mobilitas';
                    elseif ($currentRanking[0] === 'C4') $activePreset = 'storage';
                    elseif ($currentRanking[0] === 'C1') $activePreset = 'hemat';
                }

                $criteriaMeta = [
                    'C1' => ['name' => 'Harga Laptop', 'icon' => '💰', 'desc' => 'Efisiensi biaya dan kesesuaian anggaran pengadaan laptop'],
                    'C2' => ['name' => 'Performa Komputasi', 'icon' => '⚡', 'desc' => 'Kecepatan prosesor (CPU) & grafis (GPU) untuk kelancaran tugas'],
                    'C3' => ['name' => 'Kapasitas RAM', 'icon' => '🧠', 'desc' => 'Kelancaran multitasking banyak aplikasi tanpa lagging'],
                    'C4' => ['name' => 'Penyimpanan (SSD)', 'icon' => '💾', 'desc' => 'Kapasitas ruang penyimpanan dokumen kuliah dan kecepatan baca/tulis'],
                    'C5' => ['name' => 'Ketahanan Baterai', 'icon' => '🔋', 'desc' => 'Daya tahan baterai saat dipakai di kampus atau perpustakaan'],
                    'C6' => ['name' => 'Portabilitas & Berat', 'icon' => '🎒', 'desc' => 'Bobot fisik ringan yang tidak membebani saat dibawa di tas ransel'],
                ];
            @endphp

            <!-- Pilihan Kartu Kebutuhan untuk Orang Awam -->
            <div class="grid sm:grid-cols-2 gap-3" id="presetCardsContainer">
                @foreach($awamPresets as $key => $p)
                    <div onclick="selectAwamPreset('{{ $key }}', {{ json_encode($p['order']) }})"
                        data-preset="{{ $key }}"
                        class="preset-card relative p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between {{ $activePreset === $key ? 'bg-gradient-to-br from-violet-950/40 via-zinc-900 to-zinc-950 border-violet-500 ring-1 ring-violet-500/50 shadow-md' : 'bg-zinc-950/70 border-white/10 hover:border-zinc-700' }}">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">{{ $p['icon'] }}</span>
                                    <h4 class="text-xs sm:text-sm font-bold text-white">{{ $p['title'] }}</h4>
                                </div>
                                <span class="preset-indicator text-[10px] font-mono px-2 py-0.5 rounded-full {{ $activePreset === $key ? 'bg-violet-600 text-white font-bold' : 'bg-white/5 text-zinc-400' }}">
                                    {{ $p['badge'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-zinc-300 leading-snug">
                                {{ $p['desc'] }}
                            </p>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-white/5 flex items-center justify-between text-[10px] text-zinc-400 font-mono">
                            <span>Prioritas Utama: <strong class="text-cyan-300">{{ $criteriaMeta[$p['order'][0]]['name'] }}</strong></span>
                            <span class="text-violet-400 active-check {{ $activePreset === $key ? 'inline' : 'hidden' }}">✓ Terpilih</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Mode Lanjutan: Pengaturan Manual Kriteria (Untuk Dosen / Riset) -->
            <details class="group mt-4 pt-3 border-t border-white/10">
                <summary class="cursor-pointer text-xs font-mono text-zinc-400 hover:text-cyan-300 flex items-center justify-between py-1 select-none">
                    <span class="flex items-center gap-2">
                        <span>⚙️ Mode Lanjutan: Lihat / Atur Urutan Manual Kriteria Teknis (Dosen / Riset)</span>
                    </span>
                    <span class="text-xs text-zinc-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 space-y-3">
                    <p class="text-[11px] text-zinc-400">
                        Urutan kriteria di bawah ini secara otomatis tersinkronisasi saat Anda memilih kebutuhan di atas. Anda juga dapat mengubah posisi secara manual menggunakan tombol panah jika diperlukan untuk pengujian riset.
                    </p>
                    <div id="criteriaList" class="space-y-2">
                        @foreach($currentRanking as $idx => $code)
                            @php 
                                $rank = $idx + 1; 
                                $c = $criteriaMeta[$code] ?? ['name' => $code, 'icon' => '💻', 'desc' => ''];
                            @endphp
                            <div class="criterion-row flex items-center justify-between p-3.5 rounded-2xl bg-zinc-950/80 border border-white/10 hover:border-violet-500/40 transition group" data-code="{{ $code }}">
                                <div class="flex items-center gap-3">
                                    <span class="rank-badge w-7 h-7 rounded-xl bg-zinc-900 border border-white/10 flex items-center justify-center text-xs font-mono font-bold text-cyan-300 shadow-inner">
                                        #{{ $rank }}
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-white group-hover:text-cyan-200 transition">{{ $c['icon'] }} {{ $c['name'] }}</span>
                                            <span class="text-[10px] font-mono px-1.5 py-0.2 rounded-full bg-white/[0.05] text-zinc-400 border border-white/10">
                                                {{ $code }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-zinc-400 block mt-0.5">{{ $c['desc'] }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <button type="button" onclick="moveUp(this)" class="w-7 h-7 rounded-xl bg-zinc-900 hover:bg-violet-600 border border-white/10 hover:border-violet-500 flex items-center justify-center text-xs text-zinc-300 hover:text-white transition shadow-sm active:scale-95" title="Pindah Lebih Utama (Naik)">
                                        ▲
                                    </button>
                                    <button type="button" onclick="moveDown(this)" class="w-7 h-7 rounded-xl bg-zinc-900 hover:bg-violet-600 border border-white/10 hover:border-violet-500 flex items-center justify-center text-xs text-zinc-300 hover:text-white transition shadow-sm active:scale-95" title="Pindah Kurang Penting (Turun)">
                                        ▼
                                    </button>
                                </div>
                                <input type="hidden" name="ranking_kriteria[]" value="{{ $code }}" class="criterion-input">
                            </div>
                        @endforeach
                    </div>
                </div>
            </details>
        </div>

        <!-- 4. Pertanyaan 4: Filter Merek Laptop -->
        <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-mono font-bold text-zinc-300">4</span>
                    <label class="text-sm font-semibold text-white">Preferensi Merek (Brand)</label>
                </div>
                <span class="text-[11px] font-mono text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/40">Fungsi: Filter Data</span>
            </div>
            <p class="text-xs text-zinc-400">
                Pilih satu atau beberapa merek tertentu, atau pilih "Semua Merek" untuk menjangkau seluruh alternatif.
            </p>

            @php
                $savedBrands = old('merek_pilihan', $copyFrom->merek_pilihan ?? ['semua']);
                $isAll = in_array('semua', $savedBrands);
            @endphp
            <div class="space-y-3">
                <label class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl border border-zinc-800 bg-zinc-950/80 cursor-pointer hover:border-zinc-700">
                    <input type="checkbox" name="merek_pilihan[]" value="semua" id="brand_all" class="rounded border-zinc-700 text-white focus:ring-0 bg-zinc-900" {{ $isAll ? 'checked' : '' }} onchange="toggleAllBrands(this)">
                    <span class="text-xs font-bold text-white">⭐ Semua Merek (Rekomendasi Terlengkap)</span>
                </label>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1" id="specificBrands">
                    @foreach($brands as $b)
                        <label class="relative flex items-center gap-2.5 p-3 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-zinc-400 has-[:checked]:bg-zinc-800/60">
                            <input type="checkbox" name="merek_pilihan[]" value="{{ $b }}" class="brand-checkbox rounded border-zinc-700 text-white focus:ring-0 bg-zinc-900" {{ (!$isAll && in_array($b, $savedBrands)) ? 'checked' : '' }}>
                            <span class="text-xs font-medium text-zinc-200">{{ $b }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 5. Pertanyaan 5: Kondisi Laptop -->
        <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-mono font-bold text-zinc-300">5</span>
                    <label class="text-sm font-semibold text-white">Kondisi Laptop yang Diinginkan</label>
                </div>
                <span class="text-[11px] font-mono text-purple-400 bg-purple-950/40 px-2 py-0.5 rounded border border-purple-800/40">Fungsi: Subset TOPSIS & Tab</span>
            </div>
            <p class="text-xs text-zinc-400">
                Menentukan subset data laptop yang diproses TOPSIS dan tab hasil yang ditampilkan.
            </p>

            @php
                $currentKondisi = old('kondisi_pilihan', $copyFrom->kondisi_pilihan ?? 'keduanya');
            @endphp
            <div class="grid sm:grid-cols-3 gap-3">
                <label class="relative flex flex-col p-4 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-white has-[:checked]:bg-zinc-800/70 has-[:checked]:ring-1 has-[:checked]:ring-white/20">
                    <input type="radio" name="kondisi_pilihan" value="keduanya" class="sr-only" {{ $currentKondisi === 'keduanya' ? 'checked' : '' }}>
                    <span class="text-xs font-bold text-white mb-1">Keduanya (Baru & Second)</span>
                    <span class="text-[11px] text-zinc-400 leading-snug">Menampilkan 2 tab terpisah untuk membandingkan opsi baru dan second.</span>
                </label>
                <label class="relative flex flex-col p-4 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-white has-[:checked]:bg-zinc-800/70 has-[:checked]:ring-1 has-[:checked]:ring-white/20">
                    <input type="radio" name="kondisi_pilihan" value="baru" class="sr-only" {{ $currentKondisi === 'baru' ? 'checked' : '' }}>
                    <span class="text-xs font-bold text-white mb-1">Hanya Laptop Baru</span>
                    <span class="text-[11px] text-zinc-400 leading-snug">Khusus unit baru resmi dengan masa garansi penuh distributor.</span>
                </label>
                <label class="relative flex flex-col p-4 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-white has-[:checked]:bg-zinc-800/70 has-[:checked]:ring-1 has-[:checked]:ring-white/20">
                    <input type="radio" name="kondisi_pilihan" value="second" class="sr-only" {{ $currentKondisi === 'second' ? 'checked' : '' }}>
                    <span class="text-xs font-bold text-white mb-1">Hanya Laptop Second</span>
                    <span class="text-[11px] text-zinc-400 leading-snug">Opsi hemat spek tinggi bekas pakai berkualitas.</span>
                </label>
            </div>
        </div>

        <!-- 6. Pertanyaan 6: Frekuensi Membawa Laptop -->
        <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-mono font-bold text-zinc-300">6</span>
                    <label class="text-sm font-semibold text-white">Frekuensi Membawa Laptop</label>
                </div>
                <span class="text-[11px] font-mono text-zinc-400 bg-zinc-800/60 px-2 py-0.5 rounded border border-zinc-700/50">Fungsi: Informasi Mobilitas</span>
            </div>
            <p class="text-xs text-zinc-400">
                Seberapa sering Anda membawa laptop bepergian (ke kampus, kafe, atau perpustakaan).
            </p>

            @php
                $currentFreq = old('frekuensi_membawa', $copyFrom->frekuensi_membawa ?? 'Setiap Hari (Sangat Rutin)');
            @endphp
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach([
                    'Setiap Hari (Sangat Rutin)' => 'Sering dibawa dalam tas ransel ke kampus setiap hari.',
                    '2 - 3 Kali Seminggu (Moderat)' => 'Sesekali dibawa untuk kerja kelompok atau praktikum lab.',
                    'Jarang / Stasioner di Meja' => 'Mayoritas pemakaian di atas meja kamar/kost, jarang dibawa.',
                ] as $fLabel => $fDesc)
                    <label class="relative flex flex-col p-4 rounded-xl border border-zinc-800 bg-zinc-950/60 cursor-pointer hover:border-zinc-700 transition has-[:checked]:border-white has-[:checked]:bg-zinc-800/70 has-[:checked]:ring-1 has-[:checked]:ring-white/20">
                        <input type="radio" name="frekuensi_membawa" value="{{ $fLabel }}" class="sr-only" {{ $currentFreq === $fLabel ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-zinc-100 mb-1">{{ $fLabel }}</span>
                        <span class="text-[11px] text-zinc-400 leading-snug">{{ $fDesc }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between pt-4 border-t border-zinc-800">
            <span class="text-xs text-zinc-400 font-mono">
                Langkah pemrosesan: ROC &rarr; TOPSIS Baru/Second &rarr; Batch AI Explanation
            </span>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-white text-zinc-950 font-bold text-sm hover:bg-zinc-200 active:scale-[0.98] transition shadow-xl shadow-white/5">
                <span>Hitung Rekomendasi TOPSIS</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function setBudget(min, max) {
        document.getElementById('budget_min').value = min;
        document.getElementById('budget_max').value = max;
    }

    function toggleAllBrands(cb) {
        const checkboxes = document.querySelectorAll('.brand-checkbox');
        if (cb.checked) {
            checkboxes.forEach(c => c.checked = false);
        }
    }

    document.querySelectorAll('.brand-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                const allCb = document.getElementById('brand_all');
                if (allCb) allCb.checked = false;
            }
        });
    });

    function updateCriteriaRankingUI() {
        const container = document.getElementById('criteriaList');
        const rows = container.querySelectorAll('.criterion-row');
        rows.forEach((row, index) => {
            const rank = index + 1;
            const rankBadge = row.querySelector('.rank-badge');
            if (rankBadge) {
                rankBadge.textContent = '#' + rank;
            }
        });
    }

    function moveUp(btn) {
        const row = btn.closest('.criterion-row');
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('criterion-row')) {
            row.parentNode.insertBefore(row, prev);
            updateCriteriaRankingUI();
        }
    }

    function moveDown(btn) {
        const row = btn.closest('.criterion-row');
        const next = row.nextElementSibling;
        if (next && next.classList.contains('criterion-row')) {
            row.parentNode.insertBefore(next, row);
            updateCriteriaRankingUI();
        }
    function selectAwamPreset(presetKey, criteriaOrder) {
        // 1. Update visual cards styling
        document.querySelectorAll('.preset-card').forEach(card => {
            const isMatch = card.getAttribute('data-preset') === presetKey;
            const badge = card.querySelector('.preset-indicator');
            const check = card.querySelector('.active-check');

            if (isMatch) {
                card.classList.remove('bg-zinc-950/70', 'border-white/10', 'hover:border-zinc-700');
                card.classList.add('bg-gradient-to-br', 'from-violet-950/40', 'via-zinc-900', 'to-zinc-950', 'border-violet-500', 'ring-1', 'ring-violet-500/50', 'shadow-md');
                if (badge) {
                    badge.className = 'preset-indicator text-[10px] font-mono px-2 py-0.5 rounded-full bg-violet-600 text-white font-bold';
                }
                if (check) {
                    check.classList.remove('hidden');
                    check.classList.add('inline');
                }
            } else {
                card.classList.remove('bg-gradient-to-br', 'from-violet-950/40', 'via-zinc-900', 'to-zinc-950', 'border-violet-500', 'ring-1', 'ring-violet-500/50', 'shadow-md');
                card.classList.add('bg-zinc-950/70', 'border-white/10', 'hover:border-zinc-700');
                if (badge) {
                    badge.className = 'preset-indicator text-[10px] font-mono px-2 py-0.5 rounded-full bg-white/5 text-zinc-400';
                }
                if (check) {
                    check.classList.remove('inline');
                    check.classList.add('hidden');
                }
            }
        });

        // 2. Reorder criteria rows in DOM and update rank badges
        const container = document.getElementById('criteriaList');
        if (container && Array.isArray(criteriaOrder)) {
            criteriaOrder.forEach(code => {
                const row = container.querySelector(`.criterion-row[data-code="${code}"]`);
                if (row) {
                    container.appendChild(row);
                }
            });
            updateCriteriaRankingUI();
        }
    }
</script>
@endpush
@endsection
