@extends('layouts.app')

@section('title', 'Masuk - Rekomendasi Laptop SPK TOPSIS')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md relative">
        <!-- 21st.dev glow effect background -->
        <div class="absolute -inset-1 bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition duration-500"></div>

        <!-- Auth Card -->
        <div class="relative p-8 rounded-3xl bg-[#0e0e15]/90 border border-white/10 shadow-2xl backdrop-blur-2xl overflow-hidden">
            <!-- Top colorful line accent -->
            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-violet-500 via-indigo-500 to-cyan-400"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-600/30 via-indigo-600/20 to-cyan-500/30 border border-violet-500/30 text-white mb-4 shadow-[0_0_20px_rgba(124,58,237,0.3)]">
                    <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">
                    Masuk ke <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-200 via-indigo-200 to-cyan-300">Akun Anda</span>
                </h1>
                <p class="text-xs text-zinc-400 mt-1.5 leading-relaxed">
                    Sistem Pendukung Keputusan Pemilihan Laptop Mahasiswa
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5 flex items-center justify-between">
                        <span>Alamat Email (Gmail)</span>
                        <span class="text-[11px] text-zinc-400">Akun terdaftar</span>
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-zinc-950/90 border border-white/10 text-zinc-100 placeholder-zinc-400 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition"
                            placeholder="nama@gmail.com">
                        <svg class="w-4 h-4 text-zinc-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-zinc-950/90 border border-white/10 text-zinc-100 placeholder-zinc-400 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition"
                            placeholder="••••••••">
                        <svg class="w-4 h-4 text-zinc-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-3 py-3 px-4 rounded-xl bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-bold text-sm shadow-[0_0_25px_rgba(124,58,237,0.4)] hover:shadow-[0_0_35px_rgba(124,58,237,0.6)] active:scale-[0.98] transition">
                    Masuk Sekarang &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-white/[0.08] text-center text-xs text-zinc-400 space-y-3">
                <p>
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="text-cyan-300 hover:text-cyan-200 font-semibold ml-1 underline decoration-cyan-500/50 underline-offset-4">
                        Daftar Akun Baru
                    </a>
                </p>

                <!-- Kontak Bantuan Cepat -->
                <div class="flex items-center justify-center gap-2 pt-2 text-[11px] text-zinc-400">
                    <span>Butuh bantuan?</span>
                    <a href="https://wa.me/6281234567890" target="_blank" class="text-emerald-400 hover:underline flex items-center gap-1">
                        <span>WhatsApp</span>
                    </a>
                    <span>&bull;</span>
                    <a href="https://facebook.com" target="_blank" class="text-blue-400 hover:underline flex items-center gap-1">
                        <span>Facebook</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
