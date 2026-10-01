@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa - SPK Rekomendasi Laptop')

@section('content')
<div class="space-y-8">
    <!-- Top Header & Action Banner (21st.dev style with glowing border) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-zinc-900/90 via-violet-950/30 to-zinc-900/90 border border-white/10 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Halo, {{ $user->name }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-violet-500/10 text-violet-300 border border-violet-500/20">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-zinc-400 max-w-xl leading-relaxed">
                    Pusat rekomendasi laptop mahasiswa berbasis sistem pendukung keputusan multi-kriteria TOPSIS & preferensi bobot Rank Order Centroid (ROC).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('questionnaire') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm transition shadow-lg shadow-violet-600/25 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Mulai Kuisioner Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md relative overflow-hidden group hover:border-violet-500/40 transition duration-300">
            <div class="flex items-center justify-between text-xs text-zinc-400 mb-3">
                <span class="font-mono uppercase text-[11px] tracking-wider text-zinc-400">Total Sesi Kuisioner</span>
                <div class="w-8 h-8 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-white font-mono">{{ $consultations->count() }}</div>
            <p class="text-[11px] text-zinc-500 mt-2 font-mono flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                <span>Riwayat evaluasi TOPSIS tersimpan</span>
            </p>
        </div>

        @php
            $latestBest = $latestJawaban?->hasilTopsis->sortBy('peringkat')->first()?->laptop;
        @endphp
        <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md relative overflow-hidden group hover:border-cyan-500/40 transition duration-300">
            <div class="flex items-center justify-between text-xs text-zinc-400 mb-3">
                <span class="font-mono uppercase text-[11px] tracking-wider text-zinc-400">Rekomendasi Terakhir</span>
                <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
            </div>
            @if($latestBest)
                <div class="flex items-center gap-3">
                    <img src="{{ $latestBest->image_url }}" alt="{{ $latestBest->name }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 flex-shrink-0" loading="lazy">
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-white truncate group-hover:text-cyan-300 transition">
                            {{ $latestBest->name }}
                        </div>
                        <div class="text-xs font-mono text-emerald-400 font-bold">
                            Rp {{ number_format($latestBest->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-base font-semibold text-zinc-400">Belum ada kuisioner</div>
                <p class="text-[11px] text-zinc-500 mt-2 font-mono">Silakan isi formulir kuisioner baru</p>
            @endif
        </div>

        <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md relative overflow-hidden group hover:border-emerald-500/40 transition duration-300">
            <div class="flex items-center justify-between text-xs text-zinc-400 mb-3">
                <span class="font-mono uppercase text-[11px] tracking-wider text-zinc-400">Metode Pembobotan</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <div class="text-xl sm:text-2xl font-black text-emerald-400 font-mono">ROC & TOPSIS</div>
            <p class="text-[11px] text-zinc-400 mt-2 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800 text-[10px] text-zinc-300 font-mono">Kategori Baru</span>
                <span class="px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800 text-[10px] text-amber-300 font-mono">Kategori Second</span>
            </p>
        </div>
    </div>

    <!-- Riwayat Konsultasi Card -->
    <div class="rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-xl overflow-hidden shadow-2xl">
        <div class="px-6 py-5 border-b border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="space-y-0.5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                    <span>Riwayat Konsultasi & Rekomendasi Terakhir</span>
                </h2>
                <p class="text-xs text-zinc-400">Daftar seluruh sesi kuisioner yang pernah Anda jalankan.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-zinc-400 font-mono px-2.5 py-1 rounded-lg bg-zinc-900 border border-zinc-800">{{ $consultations->count() }} Sesi</span>
                <a href="{{ route('questionnaire.history') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-medium transition flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        @if($consultations->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="w-14 h-14 rounded-2xl bg-zinc-900 border border-white/10 mx-auto flex items-center justify-center text-zinc-400 mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-white">Belum Ada Riwayat Konsultasi</h3>
                <p class="text-xs text-zinc-400 max-w-sm mx-auto mt-1 mb-6 leading-relaxed">
                    Anda belum pernah mengisi kuisioner. Dapatkan rekomendasi laptop terbaik yang dipersonalisasi dengan mengisi preferensi Anda.
                </p>
                <a href="{{ route('questionnaire') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs transition shadow-lg shadow-violet-600/20">
                    Isi Kuisioner Sekarang &rarr;
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-white/10 bg-black/40 text-zinc-400 font-mono uppercase text-[11px]">
                            <th class="py-3.5 px-6">Sesi / Tanggal Pengisian</th>
                            <th class="py-3.5 px-6">Laptop Terbaik</th>
                            <th class="py-3.5 px-6">Harga</th>
                            <th class="py-3.5 px-6">Peruntukan</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($consultations->take(5) as $item)
                            @php
                                $bestItem = $item->hasilTopsis->sortBy('peringkat')->first();
                                $laptop = $bestItem?->laptop;
                            @endphp
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-4 px-6 text-xs">
                                    <div class="font-bold text-white font-mono text-sm">{{ $item->judul_sesi }}</div>
                                    <div class="text-[11px] text-cyan-300/90 font-mono flex items-center gap-1.5 mt-1">
                                        <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $item->tanggal_formatted }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($laptop)
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-10 h-10 rounded-xl object-cover border border-white/10 flex-shrink-0" loading="lazy">
                                            <div class="min-w-0">
                                                <div class="font-semibold text-white truncate max-w-[220px]">
                                                    {{ $laptop->name }}
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-mono uppercase {{ $laptop->condition === 'second' ? 'bg-amber-500/10 text-amber-300 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' }}">
                                                        {{ $laptop->condition }}
                                                    </span>
                                                    @if($bestItem)
                                                        <span class="text-[10px] font-mono text-violet-300 font-semibold">
                                                            Skor: {{ $bestItem->skor_persen }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-zinc-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-mono text-emerald-400 font-bold">
                                    {{ $laptop ? 'Rp ' . number_format($laptop->price, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-mono capitalize bg-violet-500/10 text-violet-300 border border-violet-500/20">
                                        {{ $item->peruntukan }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('recommendation.result', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/10 transition shadow-sm" title="Lihat Rekomendasi Lengkap">
                                            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Hasil AI</span>
                                        </a>
                                        <a href="{{ route('questionnaire', ['copy_from' => $item->id]) }}" class="inline-flex items-center p-2 rounded-xl text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 border border-zinc-800 transition" title="Gunakan preferensi ini untuk kuisioner baru">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                        </a>
                                        <a href="{{ route('recommendation.pdf', $item->id) }}" class="inline-flex items-center p-2 rounded-xl text-xs font-medium bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-800 transition" title="Unduh PDF">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('questionnaire.destroy', $item->id) }}" onsubmit="return confirm('Hapus riwayat kuisioner sesi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-950/40 transition" title="Hapus riwayat">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($consultations->count() > 5)
                <div class="p-4 border-t border-white/10 text-center bg-black/40">
                    <a href="{{ route('questionnaire.history') }}" class="text-xs text-zinc-400 hover:text-white transition font-medium">
                        Menampilkan 5 dari {{ $consultations->count() }} riwayat &bull; Lihat Semua di Riwayat Kuisioner &rarr;
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
