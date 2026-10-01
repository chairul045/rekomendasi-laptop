<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekomendasi Laptop #{{ $jawaban->id }}</title>
    <style>
        @page { margin: 20mm 18mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #1f2937; line-height: 1.4; }
        .header { border-bottom: 2px solid #111827; padding-bottom: 10px; margin-bottom: 16px; }
        .title { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #111827; margin: 0; }
        .subtitle { font-size: 9px; color: #4b5563; margin-top: 3px; }
        .meta-grid { width: 100%; margin-bottom: 14px; border-collapse: collapse; }
        .meta-grid td { padding: 2.5px 0; font-size: 9.5px; vertical-align: top; }
        .label { color: #6b7280; width: 130px; }
        .value { color: #111827; font-weight: 600; }
        .best-card { background-color: #f9fafb; border: 1px solid #d1d5db; border-left: 4px solid #2563eb; padding: 10px 14px; margin-bottom: 16px; border-radius: 4px; }
        .best-title { font-size: 9px; text-transform: uppercase; font-weight: bold; color: #2563eb; letter-spacing: 0.5px; }
        .best-name { font-size: 14px; font-weight: bold; color: #111827; margin: 2px 0; }
        .best-desc { font-size: 9.5px; color: #374151; }
        .ai-box { background-color: #eff6ff; border: 1px dashed #93c5fd; padding: 8px 10px; margin-top: 6px; font-size: 9px; color: #1e3a8a; border-radius: 4px; line-height: 1.35; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 14px; }
        table.data-table th { background-color: #f3f4f6; color: #1f2937; font-size: 8.5px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; border: 1px solid #e5e7eb; padding: 6px 5px; text-align: left; }
        table.data-table td { border: 1px solid #e5e7eb; padding: 5px; font-size: 9px; }
        table.data-table tr.top-row { background-color: #eff6ff; font-weight: 600; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 24px; font-size: 8.5px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: right; }
        .badge { display: inline-block; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-cost { background-color: #fef3c7; color: #92400e; }
        .badge-benefit { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Rekomendasi Pemilihan Laptop Mahasiswa</h1>
        <div class="subtitle">Sistem Pendukung Keputusan Berbasis Metode TOPSIS & Pembobotan Rank Order Centroid (ROC)</div>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="label">ID Sesi Kuisioner:</td>
            <td class="value">#{{ str_pad($jawaban->id, 5, '0', STR_PAD_LEFT) }} ({{ $jawaban->judul_sesi }})</td>
            <td class="label">Tanggal Pengisian:</td>
            <td class="value">{{ $jawaban->tanggal_formatted }}</td>
        </tr>
        <tr>
            <td class="label">Nama Mahasiswa:</td>
            <td class="value">{{ $jawaban->user->name ?? 'Mahasiswa' }} ({{ $jawaban->user->email ?? '-' }})</td>
            <td class="label">Rentang Anggaran:</td>
            <td class="value">Rp {{ number_format($jawaban->budget_min, 0, ',', '.') }} - {{ number_format($jawaban->budget_max, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Peruntukan Utama:</td>
            <td class="value">{{ $jawaban->peruntukan }}</td>
            <td class="label">Kondisi Diproses:</td>
            <td class="value">{{ strtoupper($jawaban->kondisi_pilihan) }} &bull; {{ $jawaban->frekuensi_membawa ?: 'Rutin' }}</td>
        </tr>
    </table>

    <!-- Tabel Bobot ROC -->
    <div style="font-size: 10px; font-weight: bold; margin-bottom: 4px; color: #111827;">
        Hasil Perhitungan Bobot Kriteria ROC (Rank Order Centroid):
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 45px;">Prioritas</th>
                <th>Kriteria</th>
                <th class="text-center" style="width: 60px;">Tipe</th>
                <th class="text-right" style="width: 80px;">Bobot Desimal</th>
                <th class="text-right" style="width: 80px;">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jawaban->bobotHasil as $b)
                <tr>
                    <td class="text-center">#{{ $b->prioritas }}</td>
                    <td>{{ $b->kriteria->nama }} ({{ $b->kriteria->kode }})</td>
                    <td class="text-center">
                        <span class="badge {{ $b->kriteria->tipe === 'cost' ? 'badge-cost' : 'badge-benefit' }}">
                            {{ strtoupper($b->kriteria->tipe) }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($b->bobot, 4) }}</td>
                    <td class="text-right"><strong>{{ number_format($b->bobot * 100, 1) }}%</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top Recommendation Summary -->
    @php
        $topBaru = $jawaban->hasilBaru->first();
        $topSecond = $jawaban->hasilSecond->first();
    @endphp

    @if($topBaru)
        <div class="best-card">
            <div class="best-title">Rekomendasi Utama: Kategori Laptop Baru (Peringkat #1)</div>
            <div class="best-name">{{ $topBaru->laptop->name }} - Rp {{ number_format($topBaru->laptop->price, 0, ',', '.') }}</div>
            <div class="best-desc">
                RAM: <strong>{{ $topBaru->laptop->ram_gb }}GB</strong> &nbsp;|&nbsp;
                SSD: <strong>{{ $topBaru->laptop->storage_gb }}GB</strong> &nbsp;|&nbsp;
                Baterai: <strong>{{ $topBaru->laptop->battery_hours }} Jam</strong> &nbsp;|&nbsp;
                Berat: <strong>{{ $topBaru->laptop->weight_kg }} Kg</strong> &nbsp;|&nbsp;
                Skor TOPSIS (Vi): <strong>{{ number_format($topBaru->nilai_v, 4) }} ({{ $topBaru->skor_persen }}%)</strong>
            </div>
            @if(isset($explanations[$topBaru->laptop->id]))
                <div class="ai-box">
                    <strong>Penjelasan AI:</strong> {{ $explanations[$topBaru->laptop->id] }}
                </div>
            @endif
            <div style="margin-top: 6px; font-size: 8px; color: #4b5563;">
                <strong>Link Pembelian / Cek Marketplace:</strong>
                <a href="{{ $topBaru->laptop->shopee_url }}" target="_blank" style="color: #ea580c; text-decoration: underline;">Shopee</a> &bull;
                <a href="{{ $topBaru->laptop->tokopedia_url }}" target="_blank" style="color: #16a34a; text-decoration: underline;">Tokopedia</a> &bull;
                <a href="{{ $topBaru->laptop->facebook_url }}" target="_blank" style="color: #2563eb; text-decoration: underline;">Facebook Marketplace</a> &bull;
                <a href="{{ $topBaru->laptop->blibli_url }}" target="_blank" style="color: #0284c7; text-decoration: underline;">Blibli</a> &bull;
                <a href="{{ $topBaru->laptop->bukalapak_url }}" target="_blank" style="color: #e11d48; text-decoration: underline;">Bukalapak</a>
            </div>
        </div>
    @endif

    @if($topSecond && $jawaban->kondisi_pilihan === 'keduanya')
        <div class="best-card" style="border-left-color: #d97706;">
            <div class="best-title" style="color: #d97706;">Rekomendasi Utama: Kategori Laptop Second (Peringkat #1)</div>
            <div class="best-name">{{ $topSecond->laptop->name }} - Rp {{ number_format($topSecond->laptop->price, 0, ',', '.') }}</div>
            <div class="best-desc">
                RAM: <strong>{{ $topSecond->laptop->ram_gb }}GB</strong> &nbsp;|&nbsp;
                SSD: <strong>{{ $topSecond->laptop->storage_gb }}GB</strong> &nbsp;|&nbsp;
                Baterai: <strong>{{ $topSecond->laptop->battery_hours }} Jam</strong> &nbsp;|&nbsp;
                Berat: <strong>{{ $topSecond->laptop->weight_kg }} Kg</strong> &nbsp;|&nbsp;
                Skor TOPSIS (Vi): <strong>{{ number_format($topSecond->nilai_v, 4) }} ({{ $topSecond->skor_persen }}%)</strong>
            </div>
            @if(isset($explanations[$topSecond->laptop->id]))
                <div class="ai-box" style="background-color: #fffbeb; border-color: #fcd34d; color: #78350f;">
                    <strong>Penjelasan AI:</strong> {{ $explanations[$topSecond->laptop->id] }}
                </div>
            @endif
            <div style="margin-top: 6px; font-size: 8px; color: #4b5563;">
                <strong>Link Pembelian / Cek Marketplace:</strong>
                <a href="{{ $topSecond->laptop->shopee_url }}" target="_blank" style="color: #ea580c; text-decoration: underline;">Shopee</a> &bull;
                <a href="{{ $topSecond->laptop->tokopedia_url }}" target="_blank" style="color: #16a34a; text-decoration: underline;">Tokopedia</a> &bull;
                <a href="{{ $topSecond->laptop->facebook_url }}" target="_blank" style="color: #2563eb; text-decoration: underline;">Facebook Marketplace</a> &bull;
                <a href="{{ $topSecond->laptop->blibli_url }}" target="_blank" style="color: #0284c7; text-decoration: underline;">Blibli</a> &bull;
                <a href="{{ $topSecond->laptop->bukalapak_url }}" target="_blank" style="color: #e11d48; text-decoration: underline;">Bukalapak</a>
            </div>
        </div>
    @endif

    <!-- Tabel Hasil TOPSIS -->
    <div style="font-size: 10px; font-weight: bold; margin-bottom: 4px; color: #111827;">
        Tabel Hasil Pemeringkatan TOPSIS (Top 10 Rekomendasi):
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">Rank</th>
                <th>Nama Laptop</th>
                <th class="text-center" style="width: 50px;">Kondisi</th>
                <th class="text-right" style="width: 80px;">Harga</th>
                <th class="text-center" style="width: 50px;">RAM</th>
                <th class="text-center" style="width: 55px;">Storage</th>
                <th class="text-center" style="width: 50px;">Baterai</th>
                <th class="text-center" style="width: 50px;">Berat</th>
                <th class="text-right" style="width: 65px;">Skor TOPSIS</th>
                <th class="text-center" style="width: 80px;">Beli Online</th>
            </tr>
        </thead>
        <tbody>
            @php
                $allRanked = $jawaban->hasilTopsis->take(10);
            @endphp
            @forelse($allRanked as $item)
                <tr class="{{ $item->peringkat === 1 ? 'top-row' : '' }}">
                    <td class="text-center">#{{ $item->peringkat }}</td>
                    <td>
                        {{ $item->laptop->name }}
                        @if($item->peringkat === 1)
                            <span style="font-size: 7.5px; color: #2563eb; font-weight: bold;">[BEST]</span>
                        @endif
                    </td>
                    <td class="text-center" style="text-transform: uppercase; font-size: 8px;">{{ $item->kondisi }}</td>
                    <td class="text-right">Rp {{ number_format($item->laptop->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->laptop->ram_gb }} GB</td>
                    <td class="text-center">{{ $item->laptop->storage_gb }} GB</td>
                    <td class="text-center">{{ $item->laptop->battery_hours }} Jam</td>
                    <td class="text-center">{{ $item->laptop->weight_kg }} Kg</td>
                    <td class="text-right"><strong>{{ $item->skor_persen }}%</strong></td>
                    <td class="text-center" style="font-size: 7.5px;">
                        <a href="{{ $item->laptop->shopee_url }}" target="_blank" style="color: #ea580c; text-decoration: underline;">Shopee</a> |
                        <a href="{{ $item->laptop->tokopedia_url }}" target="_blank" style="color: #16a34a; text-decoration: underline;">Tokopedia</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">Tidak ada data laptop yang cocok.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh SPK Rekomendasi Laptop Mahasiswa (Metode ROC & TOPSIS) &bull; {{ now()->format('d F Y H:i') }} WIB
    </div>
</body>
</html>
