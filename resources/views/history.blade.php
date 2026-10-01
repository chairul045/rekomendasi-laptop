@extends('layouts.app')

@section('title', 'Riwayat Kuisioner - SPK Rekomendasi Laptop TOPSIS & ROC')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto py-4">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-md">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-mono bg-blue-950/50 text-blue-300 border border-blue-800/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Multi-Session History
                </span>
                <span class="text-xs text-zinc-500 font-mono">{{ $totalHistory }} Sesi Kuisioner Tersimpan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Riwayat Kuisioner & Rekomendasi</h1>
            <p class="text-xs sm:text-sm text-zinc-400">
                Setiap kali Anda mengirimkan kuisioner, sistem TOPSIS dan perhitungan bobot ROC dicatat secara mandiri untuk transparansi penelusuran hasil.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('questionnaire') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs sm:text-sm transition shadow-sm active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Isi Kuisioner Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-2xl bg-zinc-900/30 border border-zinc-800/80 backdrop-blur-sm">
        <form method="GET" action="{{ route('questionnaire.history') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari label sesi atau peruntukan..."
                    class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-200 placeholder-zinc-500 text-xs focus:outline-none focus:border-zinc-500 transition">
                <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="sm:col-span-3">
                <select name="kondisi" class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-300 text-xs focus:outline-none focus:border-zinc-500 transition">
                    <option value="">Semua Kondisi</option>
                    <option value="keduanya" {{ request('kondisi') == 'keduanya' ? 'selected' : '' }}>Keduanya (Baru & Second)</option>
                    <option value="baru" {{ request('kondisi') == 'baru' ? 'selected' : '' }}>Hanya Baru</option>
                    <option value="second" {{ request('kondisi') == 'second' ? 'selected' : '' }}>Hanya Second</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-semibold border border-zinc-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'kondisi']))
                    <a href="{{ route('questionnaire.history') }}" class="py-2 px-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-400 hover:text-zinc-200 text-xs border border-zinc-800 transition" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- History Cards -->
    @if($consultations->isEmpty())
        <div class="p-12 text-center rounded-2xl bg-zinc-900/30 border border-zinc-800/80">
            <div class="w-12 h-12 rounded-2xl bg-zinc-800 border border-zinc-700/60 mx-auto flex items-center justify-center text-zinc-400 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-white">Belum Ada Riwayat Kuisioner</h3>
            <p class="text-xs text-zinc-400 max-w-sm mx-auto mt-1 mb-5">
                Anda belum pernah mengisi kuisioner kebutuhan. Silakan isi kuisioner pertama Anda untuk mendapatkan rekomendasi berbasis TOPSIS & ROC.
            </p>
            <a href="{{ route('questionnaire') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-zinc-950 font-semibold text-xs hover:bg-zinc-200 transition">
                Mulai Kuisioner &rarr;
            </a>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($consultations as $item)
                @php
                    $topBaru = $item->hasilTopsis->where('kondisi', 'baru')->first();
                    $topSecond = $item->hasilTopsis->where('kondisi', 'second')->first();
                    $topOverall = $topBaru ?: $topSecond;
                @endphp
                <div class="rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md p-5 flex flex-col justify-between hover:border-violet-500/40 hover:shadow-[0_0_25px_rgba(124,58,237,0.15)] transition duration-300 space-y-4">
                    <!-- Top Info -->
                    <div class="flex items-start justify-between gap-2 border-b border-white/10 pb-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <h3 class="text-xs font-bold text-white font-mono">
                                    {{ $item->judul_sesi }}
                                </h3>
                            </div>
                            <p class="text-[11px] text-cyan-300 font-mono flex items-center gap-1">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $item->tanggal_formatted }}</span>
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-mono capitalize bg-violet-950/40 text-violet-300 border border-violet-800/40">
                            {{ $item->peruntukan }}
                        </span>
                    </div>

                    <!-- Recommended Top Laptop with Image -->
                    <div class="space-y-2">
                        <div class="text-[10px] text-zinc-400 uppercase tracking-wider font-mono">Rekomendasi Teratas:</div>
                        @if($topOverall && $topOverall->laptop)
                            <div class="flex items-center gap-3">
                                <img src="{{ $topOverall->laptop->image_url }}" alt="{{ $topOverall->laptop->name }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 flex-shrink-0" loading="lazy">
                                <div class="space-y-0.5 min-w-0">
                                    <div class="text-sm font-bold text-white leading-snug line-clamp-1">
                                        {{ $topOverall->laptop->name }}
                                    </div>
                                    <div class="flex items-baseline gap-2 text-xs font-mono">
                                        <span class="text-emerald-400 font-bold">
                                            Rp {{ number_format($topOverall->laptop->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-cyan-300 text-[11px]">
                                            Skor: <strong>{{ $topOverall->skor_persen }}%</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-zinc-500 italic">Sesi telah dihitung</div>
                        @endif
                    </div>

                    <!-- Filter Parameter Badges -->
                    <div class="pt-3 border-t border-zinc-800/60">
                        <div class="flex flex-wrap gap-1.5 text-[10px]">
                            <span class="px-2 py-0.5 rounded bg-zinc-950 text-zinc-300 border border-zinc-800">
                                💰 {{ number_format($item->budget_min / 1000000, 1) }}-{{ number_format($item->budget_max / 1000000, 1) }} Jt
                            </span>
                            <span class="px-2 py-0.5 rounded bg-zinc-950 text-zinc-300 border border-zinc-800 uppercase">
                                🏷️ {{ $item->kondisi_pilihan }}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-zinc-950 text-zinc-300 border border-zinc-800">
                                🎒 {{ $item->frekuensi_membawa ?: 'Rutin' }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-3 border-t border-zinc-800/70 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('recommendation.result', $item->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-zinc-950 hover:bg-zinc-200 transition">
                                <span>Hasil</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('questionnaire', ['copy_from' => $item->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 hover:bg-zinc-700 text-zinc-300 border border-zinc-700 transition" title="Gunakan preferensi ini untuk kuisioner baru">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                <span class="hidden sm:inline">Pakai Ulang</span>
                            </a>
                            <a href="{{ route('recommendation.pdf', $item->id) }}" class="inline-flex items-center p-1.5 rounded-lg text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition" title="Unduh PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>

                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('questionnaire.destroy', $item->id) }}" onsubmit="return confirm('Hapus riwayat kuisioner sesi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-950/40 transition" title="Hapus riwayat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $consultations->links() }}
        </div>
    @endif
</div>
@endsection
