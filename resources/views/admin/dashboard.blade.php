@extends('layouts.app')

@section('title', 'Admin Dashboard - Rekomendasi Laptop SPK TOPSIS & ROC')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-md">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-amber-950/40 text-amber-300 border border-amber-800/60">
                    🛡️ Admin Panel
                </span>
                <h1 class="text-2xl font-bold text-white tracking-tight">Pusat Kendali Administrator</h1>
            </div>
            <p class="text-xs sm:text-sm text-zinc-400">
                Akses penuh untuk pengelolaan data laptop, penyesuaian kriteria TOPSIS, dan pemantauan riwayat pengguna.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-800 hover:border-zinc-700 text-xs font-medium transition">
                Lihat Mode Mahasiswa &rarr;
            </a>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm">
            <div class="text-[11px] text-zinc-500 mb-1 font-mono uppercase">Total Pengguna</div>
            <div class="text-3xl font-extrabold text-white font-mono">{{ $totalUsers }}</div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">{{ $totalMahasiswa }} Mahasiswa</div>
        </div>
        <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm">
            <div class="text-[11px] text-zinc-500 mb-1 font-mono uppercase">Dataset Laptop</div>
            <div class="text-3xl font-extrabold text-emerald-400 font-mono">{{ $totalLaptops }}</div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">{{ $totalLaptopsBaru }} Baru &bull; {{ $totalLaptopsSecond }} Second</div>
        </div>
        <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm">
            <div class="text-[11px] text-zinc-500 mb-1 font-mono uppercase">Kriteria TOPSIS</div>
            <div class="text-3xl font-extrabold text-blue-400 font-mono">{{ $criteria->count() }}</div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">Bobot Dinamis ROC</div>
        </div>
        <div class="p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm">
            <div class="text-[11px] text-zinc-500 mb-1 font-mono uppercase">Total Sesi Kuisioner</div>
            <div class="text-3xl font-extrabold text-amber-400 font-mono">{{ $totalKuisioner }}</div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">Riwayat tersimpan</div>
        </div>
    </div>

    <!-- Navigation Tabs: 3 Fungsi Utama Admin -->
    <div class="flex items-center gap-2 border-b border-zinc-800 pb-1">
        <a href="{{ route('admin.dashboard', ['tab' => 'laptops']) }}"
            class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 {{ $activeTab === 'laptops' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/60' }}">
            <span>💻 Pengelolaan Data Laptop</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $activeTab === 'laptops' ? 'bg-zinc-900 text-white' : 'bg-zinc-800 text-zinc-300' }}">
                {{ $totalLaptops }}
            </span>
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'criteria']) }}"
            class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 {{ $activeTab === 'criteria' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/60' }}">
            <span>⚙️ Pengelolaan Kriteria (6 Acuan)</span>
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}"
            class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center gap-2 {{ $activeTab === 'users' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/60' }}">
            <span>👥 Pengelolaan Pengguna & Riwayat</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $activeTab === 'users' ? 'bg-zinc-900 text-white' : 'bg-zinc-800 text-zinc-300' }}">
                {{ $totalUsers }}
            </span>
        </a>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: PENGELOLAAN DATA LAPTOP (CRUD & CSV IMPORT)       -->
    <!-- ======================================================== -->
    @if($activeTab === 'laptops')
        <div class="space-y-6">
            <!-- Action Bar & Add Laptop Form Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl bg-zinc-900/40 border border-zinc-800/80">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2.5 flex-grow">
                    <input type="hidden" name="tab" value="laptops">
                    <div class="relative min-w-[240px]">
                        <input type="text" name="search_laptop" value="{{ request('search_laptop') }}"
                            placeholder="Cari nama atau merek laptop..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-200 placeholder-zinc-500 text-xs focus:outline-none focus:border-zinc-500">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <select name="filter_condition" class="px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-300 text-xs focus:outline-none focus:border-zinc-500">
                        <option value="">Semua Kondisi</option>
                        <option value="baru" {{ request('filter_condition') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="second" {{ request('filter_condition') === 'second' ? 'selected' : '' }}>Second</option>
                    </select>

                    <button type="submit" class="py-2 px-3.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-semibold border border-zinc-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search_laptop', 'filter_condition']))
                        <a href="{{ route('admin.dashboard', ['tab' => 'laptops']) }}" class="py-2 px-2.5 rounded-xl bg-zinc-900 text-zinc-400 text-xs border border-zinc-800">✕</a>
                    @endif
                </form>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleAddLaptopForm()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition shadow-sm active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Laptop Manual</span>
                    </button>
                </div>
            </div>

            <!-- Form Tambah Laptop Manual (Hidden by default) -->
            <div id="addLaptopSection" class="hidden p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800 backdrop-blur-md space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>Formulir Tambah Data Laptop Baru</span>
                    </h3>
                    <button type="button" onclick="toggleAddLaptopForm()" class="text-xs text-zinc-400 hover:text-white">Tutup ✕</button>
                </div>

                <form method="POST" action="{{ route('admin.laptops.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Nama Laptop</label>
                            <input type="text" name="name" required placeholder="Contoh: ASUS Vivobook 14"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Merek (Brand)</label>
                            <input type="text" name="brand" required placeholder="Contoh: ASUS, Lenovo, Acer, Dell"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Harga (Rp)</label>
                            <input type="number" name="price" required placeholder="8500000" min="0"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-6 gap-3">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">Skor Performa (1-100)</label>
                            <input type="number" name="performa_komposit" required min="1" max="100" placeholder="75"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">RAM (GB)</label>
                            <input type="number" name="ram_gb" required min="2" max="128" placeholder="16"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">Storage SSD (GB)</label>
                            <input type="number" name="storage_gb" required min="64" max="4096" placeholder="512"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">Baterai (Jam)</label>
                            <input type="number" step="0.1" name="battery_hours" required min="1" max="24" placeholder="7.5"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">Berat (Kg)</label>
                            <input type="number" step="0.05" name="weight_kg" required min="0.5" max="10" placeholder="1.4"
                                class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">Kondisi</label>
                            <select name="condition" required class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-200 text-xs focus:outline-none focus:border-zinc-500">
                                <option value="baru">Baru</option>
                                <option value="second">Second</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2 rounded-xl bg-white text-zinc-950 font-bold text-xs hover:bg-zinc-200 transition">
                            Simpan Laptop
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Laptop -->
            <div class="rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800/80 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white">Daftar Laptop Terdaftar</h3>
                    <span class="text-xs font-mono text-zinc-500">{{ $laptops->total() }} Laptop Ditemukan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-zinc-800 bg-zinc-950/60 text-zinc-400 font-mono uppercase text-[10px]">
                                <th class="py-3 px-4">Nama Laptop</th>
                                <th class="py-3 px-4">Merek</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4">Kondisi</th>
                                <th class="py-3 px-4">Performa</th>
                                <th class="py-3 px-4">RAM</th>
                                <th class="py-3 px-4">Storage</th>
                                <th class="py-3 px-4">Baterai</th>
                                <th class="py-3 px-4">Berat</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @forelse($laptops as $laptop)
                                <tr class="hover:bg-zinc-800/30 transition">
                                    <td class="py-3 px-4 max-w-[240px]">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-8 h-8 rounded-lg object-cover border border-white/10 flex-shrink-0" loading="lazy">
                                            <span class="font-semibold text-white truncate">{{ $laptop->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-zinc-300 font-mono">{{ $laptop->brand }}</td>
                                    <td class="py-3 px-4 font-mono text-emerald-400">
                                        Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono uppercase {{ $laptop->condition === 'second' ? 'bg-amber-950/40 text-amber-400 border border-amber-800/40' : 'bg-emerald-950/40 text-emerald-400 border border-emerald-800/40' }}">
                                            {{ $laptop->condition }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-zinc-300">{{ $laptop->performa }}</td>
                                    <td class="py-3 px-4 font-mono text-zinc-300">{{ $laptop->ram_gb }} GB</td>
                                    <td class="py-3 px-4 font-mono text-zinc-300">{{ $laptop->storage_gb }} GB</td>
                                    <td class="py-3 px-4 font-mono text-zinc-300">{{ $laptop->battery_hours }} Jam</td>
                                    <td class="py-3 px-4 font-mono text-zinc-300">{{ $laptop->weight_kg }} Kg</td>
                                    <td class="py-3 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button"
                                                data-id="{{ $laptop->id }}"
                                                data-name="{{ $laptop->name }}"
                                                data-brand="{{ $laptop->brand }}"
                                                data-price="{{ $laptop->price }}"
                                                data-performa="{{ $laptop->performa }}"
                                                data-ram="{{ $laptop->ram_gb }}"
                                                data-storage="{{ $laptop->storage_gb }}"
                                                data-battery="{{ $laptop->battery_hours }}"
                                                data-weight="{{ $laptop->weight_kg }}"
                                                data-condition="{{ $laptop->condition }}"
                                                onclick="openEditModalFromButton(this)"
                                                class="px-2 py-1 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-[11px] transition">
                                                Ubah
                                            </button>

                                            <!-- Hapus Button -->
                                            <form method="POST" action="{{ route('admin.laptops.destroy', $laptop->id) }}" onsubmit="return confirm('Hapus laptop ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 rounded bg-rose-950/40 hover:bg-rose-900/50 text-rose-300 text-[11px] border border-rose-800/50 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-8 text-center text-zinc-500">Tidak ada data laptop ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-zinc-800">
                    {{ $laptops->links() }}
                </div>
            </div>

            <!-- Bagian Impor Massal CSV -->
            <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Impor Data Massal Laptop Melalui Berkas CSV</span>
                    </h3>
                    <span class="text-xs font-mono text-zinc-500">9 Kolom Acuan TOPSIS</span>
                </div>

                <div class="p-3.5 rounded-xl bg-zinc-950/60 border border-zinc-800 text-xs text-zinc-400 font-mono leading-relaxed">
                    <span class="text-zinc-300 font-semibold block mb-1">Urutan Format Kolom CSV:</span>
                    nama, merek, harga, performa_komposit, ram, storage, baterai, berat, kondisi
                </div>

                <form method="POST" action="{{ route('admin.laptops.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                        class="w-full text-xs text-zinc-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2 rounded-xl bg-white text-zinc-950 hover:bg-zinc-200 font-bold text-xs whitespace-nowrap transition shadow-sm">
                        Unggah CSV
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 2: PENGELOLAAN KRITERIA (6 KRITERIA ACUAN TOPSIS)    -->
    <!-- ======================================================== -->
    @if($activeTab === 'criteria')
        <div class="space-y-6">
            <div class="p-6 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-white">Konfigurasi 6 Kriteria & Pembobotan Acuan</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-violet-500/10 text-violet-300 border border-violet-500/20">Eksklusif Admin</span>
                        </div>
                        <p class="text-xs text-zinc-400 max-w-2xl leading-relaxed">
                            Atur nama kriteria, tipe <strong>Benefit</strong> (makin besar makin bagus) atau <strong>Cost</strong> (makin kecil makin ideal), serta <strong>Nilai Bobot Dasar</strong> kriteria. Mahasiswa di kuisioner hanya menentukan urutan prioritas pribadi tanpa melihat rumus bobot.
                        </p>
                    </div>
                    <div class="px-3.5 py-2 rounded-xl bg-violet-500/10 border border-violet-500/20 text-xs text-violet-300 font-mono">
                        Total Kriteria: <strong>{{ $criteria->count() }}</strong>
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($criteria as $c)
                    <div class="p-5 rounded-3xl bg-[#0e0e15]/80 border border-white/10 backdrop-blur-md space-y-4 hover:border-violet-500/40 transition duration-300">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center font-mono font-bold text-xs text-violet-300">
                                    {{ $c->kode }}
                                </span>
                                <div>
                                    <span class="text-xs font-bold text-white block">{{ $c->nama }}</span>
                                    <span class="text-[10px] text-zinc-500 font-mono">Kode: {{ $c->kode }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono uppercase font-bold {{ $c->tipe === 'cost' ? 'bg-amber-500/10 text-amber-300 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' }}">
                                    {{ $c->tipe }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.criteria.update', $c->id) }}" class="space-y-3.5">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-mono text-zinc-400 mb-1">Nama Kriteria</label>
                                <input type="text" name="nama" value="{{ old('nama', $c->nama) }}" required
                                    class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-white/10 text-zinc-100 text-xs focus:outline-none focus:border-violet-500 transition">
                            </div>

                            <div>
                                <label class="block text-[11px] font-mono text-zinc-400 mb-1">Sifat / Tipe Kriteria</label>
                                <select name="tipe" required class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-white/10 text-zinc-100 text-xs focus:outline-none focus:border-violet-500 transition">
                                    <option value="benefit" {{ $c->tipe === 'benefit' ? 'selected' : '' }}>Benefit (Semakin Tinggi Semakin Baik)</option>
                                    <option value="cost" {{ $c->tipe === 'cost' ? 'selected' : '' }}>Cost (Semakin Rendah Semakin Ideal)</option>
                                </select>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-mono text-zinc-400">Bobot Dasar Kriteria</label>
                                    <span class="text-[11px] text-cyan-400 font-mono font-bold">{{ number_format(($c->bobot ?? 0.1667) * 100, 2) }}%</span>
                                </div>
                                <div class="relative">
                                    <input type="number" step="0.0001" min="0" max="1" name="bobot" value="{{ old('bobot', $c->bobot ?? 0.1667) }}" required
                                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-white/10 text-zinc-100 font-mono text-xs focus:outline-none focus:border-violet-500 transition">
                                </div>
                                <p class="text-[10px] text-zinc-500 mt-1 font-mono">Hanya dapat diatur oleh Admin (0.0000 - 1.0000).</p>
                            </div>

                            <div>
                                <label class="block text-[11px] font-mono text-zinc-400 mb-1">Deskripsi Singkat</label>
                                <textarea name="deskripsi" rows="2" class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-white/10 text-zinc-300 text-xs focus:outline-none focus:border-violet-500 transition">{{ old('deskripsi', $c->deskripsi) }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition shadow-sm active:scale-[0.98]">
                                Simpan Perubahan {{ $c->kode }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 3: PENGELOLAAN PENGGUNA & RIWAYAT KUISIONER          -->
    <!-- ======================================================== -->
    @if($activeTab === 'users')
        <div class="space-y-6">
            <div class="rounded-2xl bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800/80 flex items-center justify-between">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-bold text-white">Daftar Pengguna & Riwayat Kuisioner</h3>
                        <p class="text-xs text-zinc-400">Pantau mahasiswa yang telah mengisi kuisioner dan telusuri hasil rekomendasinya.</p>
                    </div>
                    <span class="text-xs font-mono text-zinc-500">{{ $users->total() }} Pengguna</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-zinc-800 bg-zinc-950/60 text-zinc-400 font-mono uppercase text-[10px]">
                                <th class="py-3 px-6">ID</th>
                                <th class="py-3 px-6">Nama Pengguna</th>
                                <th class="py-3 px-6">Email</th>
                                <th class="py-3 px-6">Peran (Role)</th>
                                <th class="py-3 px-6">Jumlah Riwayat</th>
                                <th class="py-3 px-6">Terdaftar</th>
                                <th class="py-3 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @foreach($users as $u)
                                <tr class="hover:bg-zinc-800/30 transition {{ $selectedUser && $selectedUser->id === $u->id ? 'bg-blue-950/20' : '' }}">
                                    <td class="py-3.5 px-6 font-mono text-zinc-500">#{{ $u->id }}</td>
                                    <td class="py-3.5 px-6 font-semibold text-white">{{ $u->name }}</td>
                                    <td class="py-3.5 px-6 text-zinc-400">{{ $u->email }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono capitalize {{ $u->role === 'admin' ? 'bg-amber-950/40 text-amber-300 border border-amber-800/40' : 'bg-zinc-800 text-zinc-300' }}">
                                            {{ $u->role }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 font-mono text-zinc-200">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-800 text-white">
                                            {{ $u->kuisioner_jawaban_count }} Sesi
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-zinc-500 font-mono">{{ $u->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="{{ route('admin.dashboard', ['tab' => 'users', 'view_user_id' => $u->id]) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 hover:bg-zinc-700 text-zinc-100 border border-zinc-700 transition">
                                            <span>Lihat Riwayat &rarr;</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-zinc-800">
                    {{ $users->links() }}
                </div>
            </div>

            <!-- Detail Riwayat Pengguna Terpilih -->
            @if($selectedUser)
                <div class="p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800/90 backdrop-blur-md space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-white">Riwayat Kuisioner: {{ $selectedUser->name }} ({{ $selectedUser->email }})</h3>
                            <p class="text-xs text-zinc-400">Ditemukan {{ $userHistories->count() }} sesi riwayat kuisioner tersimpan.</p>
                        </div>
                        <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="text-xs text-zinc-400 hover:text-white">✕ Tutup Detail</a>
                    </div>

                    @if($userHistories->isEmpty())
                        <div class="py-8 text-center text-zinc-500 text-xs">
                            Pengguna ini belum pernah mengisi kuisioner.
                        </div>
                    @else
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($userHistories as $h)
                                <div class="p-4 rounded-xl bg-zinc-950/70 border border-zinc-800 space-y-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-white font-mono">{{ $h->judul_sesi }}</span>
                                        <span class="text-[10px] text-cyan-300 font-mono">{{ $h->tanggal_formatted }}</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400">
                                        Peruntukan: <strong class="text-zinc-200">{{ $h->peruntukan }}</strong> &bull; Kondisi: <strong class="text-zinc-200 uppercase">{{ $h->kondisi_pilihan }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-zinc-800/60">
                                        <span class="text-[10px] font-mono text-zinc-500">Budget: {{ number_format($h->budget_min / 1000000, 1) }}-{{ number_format($h->budget_max / 1000000, 1) }} Jt</span>
                                        <a href="{{ route('recommendation.result', $h->id) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 font-semibold">
                                            Buka Hasil ↗
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal Edit Laptop -->
<div id="editLaptopModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl max-w-xl w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
            <h3 class="text-sm font-bold text-white">Ubah Data Laptop</h3>
            <button type="button" onclick="closeEditModal()" class="text-zinc-400 hover:text-white text-xs">✕</button>
        </div>

        <form id="editLaptopForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Nama Laptop</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Merek (Brand)</label>
                    <input type="text" name="brand" id="edit_brand" required
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" id="edit_price" required min="0"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">Skor Performa (1-100)</label>
                    <input type="number" name="performa_komposit" id="edit_performa" required min="1" max="100"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">RAM (GB)</label>
                    <input type="number" name="ram_gb" id="edit_ram" required min="2" max="128"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">Storage SSD (GB)</label>
                    <input type="number" name="storage_gb" id="edit_storage" required min="64" max="4096"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">Baterai (Jam)</label>
                    <input type="number" step="0.1" name="battery_hours" id="edit_battery" required min="1" max="24"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">Berat (Kg)</label>
                    <input type="number" step="0.05" name="weight_kg" id="edit_weight" required min="0.5" max="10"
                        class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-100 font-mono text-xs focus:outline-none focus:border-zinc-500">
                </div>
                <div>
                    <label class="block text-[11px] font-mono text-zinc-400 mb-1">Kondisi</label>
                    <select name="condition" id="edit_condition" required class="w-full px-3 py-2 rounded-xl bg-zinc-950/80 border border-zinc-800 text-zinc-200 text-xs focus:outline-none focus:border-zinc-500">
                        <option value="baru">Baru</option>
                        <option value="second">Second</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-zinc-800">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-zinc-800 text-zinc-300 text-xs font-semibold hover:bg-zinc-700 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-white text-zinc-950 text-xs font-bold hover:bg-zinc-200 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleAddLaptopForm() {
        const form = document.getElementById('addLaptopSection');
        form.classList.toggle('hidden');
    }

    function openEditModalFromButton(btn) {
        const d = btn.dataset;
        openEditModal(d.id, d.name, d.brand, d.price, d.performa, d.ram, d.storage, d.battery, d.weight, d.condition);
    }

    function openEditModal(id, name, brand, price, performa, ram, storage, battery, weight, condition) {
        document.getElementById('editLaptopForm').action = '/admin/laptops/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_brand').value = brand;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_performa').value = performa;
        document.getElementById('edit_ram').value = ram;
        document.getElementById('edit_storage').value = storage;
        document.getElementById('edit_battery').value = battery;
        document.getElementById('edit_weight').value = weight;
        document.getElementById('edit_condition').value = condition;
        document.getElementById('editLaptopModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editLaptopModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
