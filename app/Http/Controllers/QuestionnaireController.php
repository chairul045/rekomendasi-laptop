<?php

namespace App\Http\Controllers;

use App\Models\BobotKriteriaHasil;
use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\KuisionerJawaban;
use App\Models\Laptop;
use App\Services\AiExplanationService;
use App\Services\RocService;
use App\Services\TopsisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionnaireController extends Controller
{
    /**
     * Menampilkan formulir kuisioner dengan 6 pertanyaan kriteria & preferensi.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $totalHistory = KuisionerJawaban::where('user_id', $user->id)->count();
        $latest = KuisionerJawaban::where('user_id', $user->id)->latest()->first();

        // Salin dari riwayat sebelumnya jika diminta
        $copyFrom = null;
        if ($request->filled('copy_from')) {
            $copyFrom = KuisionerJawaban::where('user_id', $user->id)
                ->find($request->input('copy_from'));
        }

        // Daftar 6 Kriteria Acuan
        $criteria = Kriteria::orderBy('id')->get();

        // Daftar Merek Laptop yang Tersedia di Basis Data
        $brands = Laptop::distinct()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->orderBy('brand')
            ->pluck('brand')
            ->values();

        return view('questionnaire', compact(
            'user',
            'totalHistory',
            'latest',
            'copyFrom',
            'criteria',
            'brands'
        ));
    }

    /**
     * Memproses pengiriman kuisioner dalam 3 tahap:
     * 1. Hitung bobot ROC berdasarkan urutan prioritas
     * 2. Jalankan algoritme TOPSIS terpisah untuk laptop baru dan/atau second
     * 3. Panggil layanan AI secara batch untuk penjelasan naratif top-N laptop
     */
    public function store(
        Request $request,
        RocService $rocService,
        TopsisService $topsisService,
        AiExplanationService $aiService
    ) {
        $validated = $request->validate([
            'budget_min' => ['required', 'numeric', 'min:0'],
            'budget_max' => ['required', 'numeric', 'gte:budget_min'],
            'peruntukan' => ['required', 'string'],
            'ranking_kriteria' => ['required', 'array', 'size:6'],
            'ranking_kriteria.*' => ['required', 'string', 'in:C1,C2,C3,C4,C5,C6'],
            'merek_pilihan' => ['required', 'array', 'min:1'],
            'kondisi_pilihan' => ['required', 'in:baru,second,keduanya'],
            'frekuensi_membawa' => ['nullable', 'string'],
            'judul' => ['nullable', 'string', 'max:100'],
            'tanggal_pengisian' => ['nullable', 'date'],
        ]);

        $user = Auth::user();

        $tanggalPengisian = !empty($validated['tanggal_pengisian'])
            ? Carbon::parse($validated['tanggal_pengisian'] . ' ' . now()->timezone('Asia/Jakarta')->format('H:i:s'), 'Asia/Jakarta')
            : now()->timezone('Asia/Jakarta');

        // 1. Simpan entitas penghubung KuisionerJawaban
        $jawaban = KuisionerJawaban::create([
            'user_id' => $user->id,
            'budget_min' => (int) $validated['budget_min'],
            'budget_max' => (int) $validated['budget_max'],
            'peruntukan' => $validated['peruntukan'],
            'ranking_kriteria' => $validated['ranking_kriteria'],
            'merek_pilihan' => $validated['merek_pilihan'],
            'kondisi_pilihan' => $validated['kondisi_pilihan'],
            'frekuensi_membawa' => $validated['frekuensi_membawa'] ?? 'Rutin',
            'judul' => $validated['judul'] ?: ('Konsultasi ' . ucfirst($validated['peruntukan']) . ' (' . $tanggalPengisian->format('d/m/Y') . ')'),
            'tanggal_pengisian' => $tanggalPengisian,
        ]);

        // -------------------------------------------------------------
        // TAHAP 1: Hitung Bobot ROC Berdasarkan Urutan Prioritas Kriteria
        // -------------------------------------------------------------
        $rocCalculated = $rocService->calculateWeights($validated['ranking_kriteria']);
        $criteriaDb = Kriteria::all()->keyBy('kode');
        $rocWeightsMap = [];

        foreach ($rocCalculated as $code => $data) {
            $kriteriaModel = $criteriaDb[$code] ?? null;
            if ($kriteriaModel) {
                BobotKriteriaHasil::create([
                    'kuisioner_jawaban_id' => $jawaban->id,
                    'kriteria_id' => $kriteriaModel->id,
                    'prioritas' => $data['prioritas'],
                    'bobot' => $data['bobot'],
                ]);
            }
            $rocWeightsMap[$code] = $data['bobot'];
        }

        // -------------------------------------------------------------
        // TAHAP 2: Filter Data & Jalankan Algoritme TOPSIS Terpisah
        // -------------------------------------------------------------
        $baseQuery = Laptop::query();

        // Filter Rentang Harga (Budget)
        $baseQuery->whereBetween('price', [$jawaban->budget_min, $jawaban->budget_max]);

        // Filter Merek (jika tidak memilih "semua")
        if (!in_array('semua', $jawaban->merek_pilihan)) {
            $baseQuery->whereIn('brand', $jawaban->merek_pilihan);
        }

        $filteredLaptops = $baseQuery->get();

        // Safety fallback jika filter terlalu ketat sehingga tidak ada laptop yang cocok
        if ($filteredLaptops->isEmpty()) {
            $filteredLaptops = Laptop::when(!in_array('semua', $jawaban->merek_pilihan), function ($q) use ($jawaban) {
                $q->whereIn('brand', $jawaban->merek_pilihan);
            })->limit(50)->get();

            if ($filteredLaptops->isEmpty()) {
                $filteredLaptops = Laptop::limit(50)->get();
            }
        }

        $topLaptopsForAi = [];

        // 2A. Subset Laptop Baru
        if (in_array($jawaban->kondisi_pilihan, ['baru', 'keduanya'])) {
            $laptopsBaru = $filteredLaptops->where('condition', 'baru');
            if ($laptopsBaru->isEmpty()) {
                $laptopsBaru = Laptop::where('condition', 'baru')->limit(30)->get();
            }

            $topsisBaru = $topsisService->calculate($laptopsBaru, $rocWeightsMap);
            foreach ($topsisBaru['ranked'] as $item) {
                HasilTopsis::create([
                    'kuisioner_jawaban_id' => $jawaban->id,
                    'laptop_id' => $item['laptop_id'],
                    'kondisi' => 'baru',
                    'nilai_v' => $item['nilai_v'],
                    'peringkat' => $item['peringkat'],
                ]);
            }

            $topLaptopsForAi = array_merge($topLaptopsForAi, array_slice($topsisBaru['ranked'], 0, 3));
        }

        // 2B. Subset Laptop Second
        if (in_array($jawaban->kondisi_pilihan, ['second', 'keduanya'])) {
            $laptopsSecond = $filteredLaptops->where('condition', 'second');
            if ($laptopsSecond->isEmpty()) {
                $laptopsSecond = Laptop::where('condition', 'second')->limit(30)->get();
            }

            $topsisSecond = $topsisService->calculate($laptopsSecond, $rocWeightsMap);
            foreach ($topsisSecond['ranked'] as $item) {
                HasilTopsis::create([
                    'kuisioner_jawaban_id' => $jawaban->id,
                    'laptop_id' => $item['laptop_id'],
                    'kondisi' => 'second',
                    'nilai_v' => $item['nilai_v'],
                    'peringkat' => $item['peringkat'],
                ]);
            }

            $topLaptopsForAi = array_merge($topLaptopsForAi, array_slice($topsisSecond['ranked'], 0, 3));
        }

        // -------------------------------------------------------------
        // TAHAP 3: Batch AI Explanation Cache untuk Top-N Laptops
        // -------------------------------------------------------------
        $aiService->generateForTopLaptops($jawaban, $topLaptopsForAi, $rocWeightsMap, 6);

        return redirect()->route('recommendation.result', $jawaban->id)
            ->with('success', 'Perhitungan bobot ROC dan analisis TOPSIS berhasil diproses!');
    }

    /**
     * Menampilkan hasil rekomendasi dengan kartu laptop, skor kecocokan, dan penjelasan AI.
     */
    public function result(KuisionerJawaban $jawaban): View
    {
        abort_unless(
            $jawaban->user_id === Auth::id() || (Auth::user() && Auth::user()->role === 'admin'),
            403
        );

        $jawaban->load([
            'user',
            'bobotHasil.kriteria',
            'hasilBaru.laptop',
            'hasilSecond.laptop',
            'penjelasanAi',
        ]);

        $explanations = $jawaban->penjelasanAi->pluck('penjelasan', 'laptop_id')->toArray();
        $totalHistory = KuisionerJawaban::where('user_id', $jawaban->user_id)->count();

        return view('result', compact('jawaban', 'explanations', 'totalHistory'));
    }

    /**
     * Menampilkan riwayat kuisioner pengguna.
     */
    public function history(Request $request): View
    {
        $user = Auth::user();

        $query = KuisionerJawaban::where('user_id', $user->id)
            ->with(['bobotHasil.kriteria', 'hasilTopsis.laptop']);

        if ($request->filled('peruntukan')) {
            $query->where('peruntukan', $request->peruntukan);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi_pilihan', $request->kondisi);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', $s)
                  ->orWhere('peruntukan', 'like', $s);
            });
        }

        $consultations = $query->orderByDesc('created_at')->paginate(9)->withQueryString();
        $totalHistory = KuisionerJawaban::where('user_id', $user->id)->count();

        return view('history', compact('consultations', 'totalHistory'));
    }

    /**
     * Menghapus sesi kuisioner dan relasi turunannya.
     */
    public function destroy(KuisionerJawaban $jawaban)
    {
        abort_unless($jawaban->user_id === Auth::id() || (Auth::user() && Auth::user()->role === 'admin'), 403);

        $jawaban->delete();

        return redirect()->back()->with('success', 'Riwayat kuisioner berhasil dihapus.');
    }

    /**
     * Cetak Laporan PDF Hasil Rekomendasi
     */
    public function pdf(KuisionerJawaban $jawaban)
    {
        abort_unless($jawaban->user_id === Auth::id() || (Auth::user() && Auth::user()->role === 'admin'), 403);

        $jawaban->load(['user', 'bobotHasil.kriteria', 'hasilBaru.laptop', 'hasilSecond.laptop', 'penjelasanAi']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.result', [
            'jawaban' => $jawaban,
            'explanations' => $jawaban->penjelasanAi->pluck('penjelasan', 'laptop_id')->toArray(),
        ]);

        return $pdf->download('laporan-spk-laptop-' . $jawaban->id . '.pdf');
    }
}
