@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Laptop - SPK TOPSIS & ROC')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto py-4">
    <!-- Header Navigation & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-zinc-800">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono bg-emerald-950/50 text-emerald-400 border border-emerald-800/50 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Analisis TOPSIS & Narasi AI Selesai
                </span>
                <span class="text-xs text-cyan-300 font-mono flex items-center gap-1 px-2.5 py-1 rounded-full bg-cyan-950/40 border border-cyan-800/40">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Tanggal Pengisian: <strong>{{ $jawaban->tanggal_formatted }}</strong></span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                {{ $jawaban->judul_sesi }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('questionnaire.history') }}" class="px-3.5 py-2 rounded-xl bg-[#0e0e15] hover:bg-zinc-800 text-zinc-300 border border-white/10 text-xs font-medium transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Semua Riwayat ({{ $totalHistory ?? 0 }})</span>
            </a>
            <a href="{{ route('questionnaire', ['copy_from' => $jawaban->id]) }}" class="px-3.5 py-2 rounded-xl bg-[#0e0e15] hover:bg-zinc-800 text-zinc-300 border border-white/10 text-xs font-medium transition flex items-center gap-1.5" title="Gunakan preferensi ini untuk kuisioner baru">
                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                <span>Pakai Preferensi Ini</span>
            </a>
            <a href="{{ route('questionnaire') }}" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-semibold shadow-sm transition">
                Kuisioner Baru
            </a>
            <a href="{{ route('recommendation.pdf', $jawaban->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-zinc-950 hover:bg-zinc-200 font-bold text-xs transition shadow-sm active:scale-[0.98]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- Parameter Profil & Filter Kuisioner Sesi Ini -->
    <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-3">
        <div class="text-[11px] font-mono text-zinc-400 uppercase tracking-wider flex items-center justify-between">
            <span>Profil Kebutuhan & Filter Terpilih</span>
            <span class="text-zinc-500 font-mono">ID Sesi: #{{ $jawaban->id }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 text-xs">
            <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800">
                <span class="text-[10px] text-zinc-500 block mb-0.5 font-mono">Rentang Budget</span>
                <span class="font-bold text-white font-mono">Rp {{ number_format($jawaban->budget_min, 0, ',', '.') }} - {{ number_format($jawaban->budget_max, 0, ',', '.') }}</span>
            </div>
            <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800">
                <span class="text-[10px] text-zinc-500 block mb-0.5 font-mono">Peruntukan (Label)</span>
                <span class="font-bold text-white">{{ $jawaban->peruntukan }}</span>
            </div>
            <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800">
                <span class="text-[10px] text-zinc-500 block mb-0.5 font-mono">Kondisi Diproses</span>
                <span class="font-bold text-white uppercase font-mono">{{ $jawaban->kondisi_pilihan }}</span>
            </div>
            <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800">
                <span class="text-[10px] text-zinc-500 block mb-0.5 font-mono">Filter Merek</span>
                <span class="font-bold text-white truncate block">
                    {{ in_array('semua', $jawaban->merek_pilihan ?? []) ? 'Semua Merek' : implode(', ', $jawaban->merek_pilihan ?? []) }}
                </span>
            </div>
            <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800">
                <span class="text-[10px] text-zinc-500 block mb-0.5 font-mono">Frekuensi Bawa</span>
                <span class="font-bold text-white">{{ $jawaban->frekuensi_membawa ?: 'Rutin' }}</span>
            </div>
        </div>
    </div>

    <!-- Bobot ROC Kriteria Transparan -->
    <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="space-y-0.5">
                <h3 class="text-xs font-mono font-bold text-white uppercase tracking-wider">
                    Transparansi Perhitungan Bobot ROC (Rank Order Centroid)
                </h3>
                <p class="text-[11px] text-zinc-400">
                    Dihitung otomatis dari urutan prioritas yang Anda tentukan: $W_i = \frac{1}{6} \sum_{j=i}^{6} \frac{1}{j}$ &bull; $\sum W_i = 1.0000$
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 text-center text-xs">
            @foreach($jawaban->bobotHasil as $b)
                <div class="p-3 rounded-xl bg-zinc-950/70 border border-zinc-800 relative overflow-hidden">
                    <div class="text-[10px] font-mono text-zinc-400 flex items-center justify-between mb-1">
                        <span>#{{ $b->prioritas }}</span>
                        <span class="text-[9px] px-1 py-0.2 rounded {{ $b->kriteria->tipe === 'cost' ? 'bg-amber-950/50 text-amber-300' : 'bg-emerald-950/50 text-emerald-300' }}">
                            {{ strtoupper($b->kriteria->tipe) }}
                        </span>
                    </div>
                    <div class="text-xs font-bold text-white">{{ $b->kriteria->nama }}</div>
                    <div class="text-base font-extrabold text-blue-400 font-mono mt-1">
                        {{ number_format($b->bobot * 100, 1) }}%
                    </div>
                    <span class="text-[10px] text-zinc-500 font-mono">w = {{ number_format($b->bobot, 4) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB PILIHAN: Laptop Baru vs Laptop Second -->
    @php
        $showTwoTabs = $jawaban->kondisi_pilihan === 'keduanya';
        $hasilBaru = $jawaban->hasilBaru;
        $hasilSecond = $jawaban->hasilSecond;
    @endphp

    <div class="space-y-6" x-data="{ tab: '{{ $jawaban->kondisi_pilihan === 'second' ? 'second' : 'baru' }}' }">
        @if($showTwoTabs)
            <div class="flex items-center gap-2 border-b border-zinc-800 pb-1">
                <button type="button"
                    onclick="switchResultTab('baru')"
                    id="tabBtnBaru"
                    class="tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 bg-white text-zinc-950 shadow-sm">
                    <span>Laptop Baru</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-zinc-900 text-white font-semibold">
                        {{ $hasilBaru->count() }}
                    </span>
                </button>
                <button type="button"
                    onclick="switchResultTab('second')"
                    id="tabBtnSecond"
                    class="tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 text-zinc-400 hover:text-white hover:bg-zinc-900/60">
                    <span>Laptop Second</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-zinc-800 text-zinc-300">
                        {{ $hasilSecond->count() }}
                    </span>
                </button>
            </div>
        @else
            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-mono bg-zinc-900 border border-zinc-800 text-zinc-200">
                    Kategori Hasil: Laptop {{ ucfirst($jawaban->kondisi_pilihan) }} ({{ $jawaban->hasilTopsis->count() }} unit)
                </span>
            </div>
        @endif

        <!-- 1. TAB LAPTOP BARU -->
        @if(in_array($jawaban->kondisi_pilihan, ['baru', 'keduanya']))
            <div id="tabContentBaru" class="space-y-6 {{ $jawaban->kondisi_pilihan === 'second' ? 'hidden' : '' }}">
                @if($hasilBaru->isEmpty())
                    <div class="p-12 text-center rounded-3xl bg-[#0e0e15]/60 border border-white/10 text-zinc-400 text-xs">
                        Tidak ada laptop baru yang memenuhi rentang harga dan filter merek yang dipilih.
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($hasilBaru as $item)
                            @php
                                $laptop = $item->laptop;
                                $narasi = $explanations[$laptop->id] ?? null;
                                $isTop = $item->peringkat === 1;
                            @endphp
                            <div class="group relative rounded-3xl border {{ $isTop ? 'bg-gradient-to-br from-violet-950/30 via-[#0e0e15] to-[#09090d] border-violet-500/50 shadow-[0_0_40px_rgba(124,58,237,0.25)] ring-1 ring-violet-500/30' : 'bg-[#0e0e15]/80 border-white/10 hover:border-violet-500/40' }} p-6 sm:p-7 backdrop-blur-xl transition duration-300 space-y-6">
                                <!-- Main Card Header with Image and Details -->
                                <div class="flex flex-col md:flex-row items-stretch gap-6">
                                    <!-- Laptop Image with overlays -->
                                    <div class="relative w-full md:w-64 h-52 md:h-auto min-h-[190px] rounded-2xl overflow-hidden bg-zinc-950 flex-shrink-0 border border-white/10 shadow-inner">
                                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500 filter brightness-95 group-hover:brightness-105"
                                            loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>

                                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase tracking-wider bg-black/70 backdrop-blur-md text-white border border-white/20">
                                                {{ $laptop->brand }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-emerald-500 text-white">
                                                Baru Resmi
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-[11px] font-mono">
                                            <span class="text-zinc-300 bg-black/70 px-2 py-0.5 rounded-lg border border-white/10">⚡ Skor: {{ $laptop->performa }}/100</span>
                                            <span class="text-cyan-300 font-bold">#{{ $item->peringkat }}</span>
                                        </div>
                                    </div>

                                    <!-- Information & Score -->
                                    <div class="flex-grow flex flex-col justify-between space-y-4">
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl text-xs font-mono font-bold {{ $isTop ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white shadow-md shadow-violet-600/30' : 'bg-zinc-800 text-zinc-300' }}">
                                                        {{ $isTop ? '⭐ Peringkat #1 (Pilihan Terbaik)' : 'Peringkat #' . $item->peringkat }}
                                                    </span>
                                                    <span class="text-xs font-mono text-zinc-400 uppercase tracking-wider">{{ $laptop->brand }}</span>
                                                </div>
                                                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                                    {{ $laptop->name }}
                                                </h2>
                                                <div class="text-2xl font-extrabold font-mono text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-300 pt-1">
                                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <!-- Skor Kecocokan TOPSIS (Vi) -->
                                            <div class="p-4 rounded-2xl bg-zinc-950/90 border border-white/10 text-center sm:min-w-[170px] self-start shadow-inner">
                                                <div class="text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-0.5">Kecocokan TOPSIS</div>
                                                <div class="text-3xl font-extrabold text-white font-mono">
                                                    {{ $item->skor_persen }}%
                                                </div>
                                                <div class="mt-2 w-full bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-violet-500 via-indigo-500 to-cyan-400 h-1.5 rounded-full" style="width: {{ min(100, $item->skor_persen) }}%"></div>
                                                </div>
                                                <span class="text-[10px] text-zinc-400 mt-1.5 block font-mono">Nilai V: {{ number_format($item->nilai_v, 4) }}</span>
                                            </div>
                                        </div>

                                        <!-- Spesifikasi Ringkas 5 Kolom -->
                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-1">
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Performa CPU+GPU</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->performa }} / 100</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">RAM Multitasking</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->ram_gb }} GB</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Storage SSD</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->storage_gb }} GB</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Daya Baterai</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->battery_hours }} Jam</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Berat Portabilitas</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->weight_kg }} Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penjelasan Naratif AI (Tersimpan di Cache Tabel penjelasan_ai) -->
                                @if($narasi)
                                    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-violet-950/30 via-zinc-950/80 to-cyan-950/20 border border-violet-500/30 space-y-2 shadow-inner">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-xs font-mono font-bold text-cyan-300">
                                                <svg class="w-4 h-4 text-cyan-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                <span>Analisis Rekomendasi Cerdas (AI Generated):</span>
                                            </div>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-violet-500/20 text-violet-300 border border-violet-500/30">
                                                AI Assisted
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-zinc-200 leading-relaxed font-sans">
                                            {{ $narasi }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Link Pembelian Marketplace (Shopee, Tokopedia, Facebook Marketplace, Blibli, Bukalapak) -->
                                <div class="pt-3.5 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 text-xs text-zinc-300">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="font-semibold text-white">Cek Ketersediaan / Beli:</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Shopee -->
                                        <a href="{{ $laptop->shopee_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#ee4d2d]/15 text-[#ff6740] hover:bg-[#ee4d2d] hover:text-white border border-[#ee4d2d]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Shopee">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.5 7.5h-2.25a5.25 5.25 0 0 0-10.5 0H4.5A1.5 1.5 0 0 0 3 9v11.25A2.25 2.25 0 0 0 5.25 22.5h13.5A2.25 2.25 0 0 0 21 20.25V9a1.5 1.5 0 0 0-1.5-1.5zm-7.5-3.75a3.75 3.75 0 0 1 3.75 3.75h-7.5a3.75 3.75 0 0 1 3.75-3.75z"/></svg>
                                            <span>Shopee</span>
                                        </a>

                                        <!-- Tokopedia -->
                                        <a href="{{ $laptop->tokopedia_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#03ac0e]/15 text-[#42d14e] hover:bg-[#03ac0e] hover:text-white border border-[#03ac0e]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Tokopedia">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.5h-2v-2h2v2zm0-4h-2V7h2v5.5z"/></svg>
                                            <span>Tokopedia</span>
                                        </a>

                                        <!-- Facebook Marketplace -->
                                        <a href="{{ $laptop->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#1877f2]/15 text-[#4c9eff] hover:bg-[#1877f2] hover:text-white border border-[#1877f2]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Facebook Marketplace">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            <span>Facebook</span>
                                        </a>

                                        <!-- Blibli -->
                                        <a href="{{ $laptop->blibli_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#0095da]/15 text-[#38bdf8] hover:bg-[#0095da] hover:text-white border border-[#0095da]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Blibli">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                            <span>Blibli</span>
                                        </a>

                                        <!-- Bukalapak -->
                                        <a href="{{ $laptop->bukalapak_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#e31e52]/15 text-[#fb7185] hover:bg-[#e31e52] hover:text-white border border-[#e31e52]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Bukalapak">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0-9 9c0 4.97 4.03 9 9 9s9-4.03 9-9-4.03-9-9-9zm0 15c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/></svg>
                                            <span>Bukalapak</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- 2. TAB LAPTOP SECOND -->
        @if(in_array($jawaban->kondisi_pilihan, ['second', 'keduanya']))
            <div id="tabContentSecond" class="space-y-6 {{ $jawaban->kondisi_pilihan === 'baru' ? 'hidden' : ($showTwoTabs ? 'hidden' : '') }}">
                @if($hasilSecond->isEmpty())
                    <div class="p-12 text-center rounded-3xl bg-[#0e0e15]/60 border border-white/10 text-zinc-400 text-xs">
                        Tidak ada laptop second yang memenuhi rentang harga dan filter merek yang dipilih.
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($hasilSecond as $item)
                            @php
                                $laptop = $item->laptop;
                                $narasi = $explanations[$laptop->id] ?? null;
                                $isTop = $item->peringkat === 1;
                            @endphp
                            <div class="group relative rounded-3xl border {{ $isTop ? 'bg-gradient-to-br from-amber-950/30 via-[#0e0e15] to-[#09090d] border-amber-500/50 shadow-[0_0_40px_rgba(245,158,11,0.2)] ring-1 ring-amber-500/30' : 'bg-[#0e0e15]/80 border-white/10 hover:border-amber-500/40' }} p-6 sm:p-7 backdrop-blur-xl transition duration-300 space-y-6">
                                <!-- Main Card Header with Image and Details -->
                                <div class="flex flex-col md:flex-row items-stretch gap-6">
                                    <!-- Laptop Image with overlays -->
                                    <div class="relative w-full md:w-64 h-52 md:h-auto min-h-[190px] rounded-2xl overflow-hidden bg-zinc-950 flex-shrink-0 border border-white/10 shadow-inner">
                                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500 filter brightness-95 group-hover:brightness-105"
                                            loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>

                                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold uppercase tracking-wider bg-black/70 backdrop-blur-md text-white border border-white/20">
                                                {{ $laptop->brand }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-amber-500 text-zinc-950">
                                                Unit Second
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-[11px] font-mono">
                                            <span class="text-zinc-300 bg-black/70 px-2 py-0.5 rounded-lg border border-white/10">⚡ Skor: {{ $laptop->performa }}/100</span>
                                            <span class="text-amber-300 font-bold">#{{ $item->peringkat }}</span>
                                        </div>
                                    </div>

                                    <!-- Information & Score -->
                                    <div class="flex-grow flex flex-col justify-between space-y-4">
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl text-xs font-mono font-bold {{ $isTop ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-zinc-950 shadow-md shadow-amber-500/30' : 'bg-zinc-800 text-zinc-300' }}">
                                                        {{ $isTop ? '⭐ Peringkat #1 (Pilihan Terbaik)' : 'Peringkat #' . $item->peringkat }}
                                                    </span>
                                                    <span class="text-xs font-mono text-zinc-400 uppercase tracking-wider">{{ $laptop->brand }}</span>
                                                </div>
                                                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                                    {{ $laptop->name }}
                                                </h2>
                                                <div class="text-2xl font-extrabold font-mono text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-yellow-200 pt-1">
                                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <!-- Skor Kecocokan TOPSIS (Vi) -->
                                            <div class="p-4 rounded-2xl bg-zinc-950/90 border border-white/10 text-center sm:min-w-[170px] self-start shadow-inner">
                                                <div class="text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-0.5">Kecocokan TOPSIS</div>
                                                <div class="text-3xl font-extrabold text-white font-mono">
                                                    {{ $item->skor_persen }}%
                                                </div>
                                                <div class="mt-2 w-full bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-emerald-400 h-1.5 rounded-full" style="width: {{ min(100, $item->skor_persen) }}%"></div>
                                                </div>
                                                <span class="text-[10px] text-zinc-400 mt-1.5 block font-mono">Nilai V: {{ number_format($item->nilai_v, 4) }}</span>
                                            </div>
                                        </div>

                                        <!-- Spesifikasi Ringkas 5 Kolom -->
                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-1">
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Performa CPU+GPU</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->performa }} / 100</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">RAM Multitasking</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->ram_gb }} GB</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Storage SSD</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->storage_gb }} GB</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Daya Baterai</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->battery_hours }} Jam</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-zinc-950/70 border border-white/5 text-xs">
                                                <span class="text-[10px] text-zinc-400 block font-mono">Berat Portabilitas</span>
                                                <span class="font-bold text-zinc-200 font-mono">{{ $laptop->weight_kg }} Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penjelasan Naratif AI (Tersimpan di Cache Tabel penjelasan_ai) -->
                                @if($narasi)
                                    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-amber-950/30 via-zinc-950/80 to-yellow-950/20 border border-amber-500/30 space-y-2 shadow-inner">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-xs font-mono font-bold text-amber-300">
                                                <svg class="w-4 h-4 text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                <span>Analisis Rekomendasi Cerdas (AI Generated):</span>
                                            </div>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                AI Assisted
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-zinc-200 leading-relaxed font-sans">
                                            {{ $narasi }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Link Pembelian Marketplace (Shopee, Tokopedia, Facebook Marketplace, Blibli, Bukalapak) -->
                                <div class="pt-3.5 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 text-xs text-zinc-300">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="font-semibold text-white">Cek Ketersediaan / Beli Unit Second:</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Shopee -->
                                        <a href="{{ $laptop->shopee_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#ee4d2d]/15 text-[#ff6740] hover:bg-[#ee4d2d] hover:text-white border border-[#ee4d2d]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Shopee">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.5 7.5h-2.25a5.25 5.25 0 0 0-10.5 0H4.5A1.5 1.5 0 0 0 3 9v11.25A2.25 2.25 0 0 0 5.25 22.5h13.5A2.25 2.25 0 0 0 21 20.25V9a1.5 1.5 0 0 0-1.5-1.5zm-7.5-3.75a3.75 3.75 0 0 1 3.75 3.75h-7.5a3.75 3.75 0 0 1 3.75-3.75z"/></svg>
                                            <span>Shopee</span>
                                        </a>

                                        <!-- Tokopedia -->
                                        <a href="{{ $laptop->tokopedia_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#03ac0e]/15 text-[#42d14e] hover:bg-[#03ac0e] hover:text-white border border-[#03ac0e]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Tokopedia">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.5h-2v-2h2v2zm0-4h-2V7h2v5.5z"/></svg>
                                            <span>Tokopedia</span>
                                        </a>

                                        <!-- Facebook Marketplace -->
                                        <a href="{{ $laptop->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#1877f2]/15 text-[#4c9eff] hover:bg-[#1877f2] hover:text-white border border-[#1877f2]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Facebook Marketplace">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            <span>Facebook</span>
                                        </a>

                                        <!-- Blibli -->
                                        <a href="{{ $laptop->blibli_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#0095da]/15 text-[#38bdf8] hover:bg-[#0095da] hover:text-white border border-[#0095da]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Blibli">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                            <span>Blibli</span>
                                        </a>

                                        <!-- Bukalapak -->
                                        <a href="{{ $laptop->bukalapak_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#e31e52]/15 text-[#fb7185] hover:bg-[#e31e52] hover:text-white border border-[#e31e52]/30 transition shadow-sm"
                                            title="Cari '{{ $laptop->brand }} {{ $laptop->name }}' di Bukalapak">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0-9 9c0 4.97 4.03 9 9 9s9-4.03 9-9-4.03-9-9-9zm0 15c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/></svg>
                                            <span>Bukalapak</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function switchResultTab(tabName) {
        const btnBaru = document.getElementById('tabBtnBaru');
        const btnSecond = document.getElementById('tabBtnSecond');
        const contentBaru = document.getElementById('tabContentBaru');
        const contentSecond = document.getElementById('tabContentSecond');

        if (!btnBaru || !btnSecond) return;

        if (tabName === 'baru') {
            btnBaru.className = 'tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 bg-white text-zinc-950 shadow-sm';
            btnSecond.className = 'tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 text-zinc-400 hover:text-white hover:bg-zinc-900/60';
            contentBaru.classList.remove('hidden');
            contentSecond.classList.add('hidden');
        } else {
            btnSecond.className = 'tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 bg-white text-zinc-950 shadow-sm';
            btnBaru.className = 'tab-btn px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 text-zinc-400 hover:text-white hover:bg-zinc-900/60';
            contentSecond.classList.remove('hidden');
            contentBaru.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
