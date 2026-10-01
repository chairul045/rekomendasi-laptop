<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KuisionerJawaban;
use App\Models\Laptop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard Admin: menyediakan 3 fungsi utama (Laptop, Kriteria, Pengguna).
     */
    public function dashboard(Request $request): View
    {
        $activeTab = $request->query('tab', 'laptops');

        // Statistik Utama
        $totalUsers = User::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalLaptops = Laptop::count();
        $totalLaptopsBaru = Laptop::where('condition', 'baru')->count();
        $totalLaptopsSecond = Laptop::where('condition', 'second')->count();
        $totalKuisioner = KuisionerJawaban::count();

        // 1. Pengelolaan Data Laptop (dengan filter & pencarian)
        $laptopQuery = Laptop::query();
        if ($request->filled('search_laptop')) {
            $s = '%' . $request->search_laptop . '%';
            $laptopQuery->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('brand', 'like', $s);
            });
        }
        if ($request->filled('filter_condition')) {
            $laptopQuery->where('condition', $request->filter_condition);
        }
        $laptops = $laptopQuery->orderByDesc('id')->paginate(15, ['*'], 'laptops_page')->withQueryString();

        // 2. Pengelolaan Kriteria (6 kriteria acuan)
        $criteria = Kriteria::orderBy('id')->get();

        // 3. Pengelolaan Pengguna & Riwayat
        $userQuery = User::withCount('kuisionerJawaban');
        if ($request->filled('search_user')) {
            $su = '%' . $request->search_user . '%';
            $userQuery->where(function ($q) use ($su) {
                $q->where('name', 'like', $su)
                  ->orWhere('email', 'like', $su);
            });
        }
        $users = $userQuery->orderByDesc('id')->paginate(10, ['*'], 'users_page')->withQueryString();

        // Ambil riwayat pengguna tertentu jika ada query view_user_id
        $selectedUser = null;
        $userHistories = collect();
        if ($request->filled('view_user_id')) {
            $selectedUser = User::find($request->view_user_id);
            if ($selectedUser) {
                $userHistories = KuisionerJawaban::where('user_id', $selectedUser->id)
                    ->with(['bobotHasil.kriteria', 'hasilTopsis.laptop'])
                    ->latest()
                    ->get();
            }
        }

        return view('admin.dashboard', compact(
            'activeTab',
            'totalUsers',
            'totalMahasiswa',
            'totalLaptops',
            'totalLaptopsBaru',
            'totalLaptopsSecond',
            'totalKuisioner',
            'laptops',
            'criteria',
            'users',
            'selectedUser',
            'userHistories'
        ));
    }

    /**
     * Tambah Data Laptop Manual
     */
    public function storeLaptop(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'performa_komposit' => ['required', 'integer', 'min:1', 'max:100'],
            'ram_gb' => ['required', 'integer', 'min:2', 'max:128'],
            'storage_gb' => ['required', 'integer', 'min:64', 'max:4096'],
            'battery_hours' => ['required', 'numeric', 'min:1', 'max:24'],
            'weight_kg' => ['required', 'numeric', 'min:0.5', 'max:10'],
            'condition' => ['required', 'in:baru,second'],
        ]);

        $validated['processor_score'] = $validated['performa_komposit'];
        $validated['vga_score'] = $validated['performa_komposit'];

        Laptop::create($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'laptops'])
            ->with('success', 'Data laptop berhasil ditambahkan.');
    }

    /**
     * Ubah Data Laptop Manual
     */
    public function updateLaptop(Request $request, Laptop $laptop)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'performa_komposit' => ['required', 'integer', 'min:1', 'max:100'],
            'ram_gb' => ['required', 'integer', 'min:2', 'max:128'],
            'storage_gb' => ['required', 'integer', 'min:64', 'max:4096'],
            'battery_hours' => ['required', 'numeric', 'min:1', 'max:24'],
            'weight_kg' => ['required', 'numeric', 'min:0.5', 'max:10'],
            'condition' => ['required', 'in:baru,second'],
        ]);

        $validated['processor_score'] = $validated['performa_komposit'];
        $validated['vga_score'] = $validated['performa_komposit'];

        $laptop->update($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'laptops'])
            ->with('success', 'Data laptop "' . $laptop->name . '" berhasil diperbarui.');
    }

    /**
     * Hapus Data Laptop Manual
     */
    public function destroyLaptop(Laptop $laptop)
    {
        $name = $laptop->name;
        $laptop->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'laptops'])
            ->with('success', 'Data laptop "' . $name . '" berhasil dihapus.');
    }

    /**
     * Update Kriteria (Nama dan Tipe Benefit / Cost)
     */
    public function updateCriteria(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:benefit,cost'],
            'bobot' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kriteria->update($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'criteria'])
            ->with('success', 'Kriteria ' . $kriteria->kode . ' (' . $kriteria->nama . ') berhasil diperbarui.');
    }

    /**
     * Impor Data Massal Laptop melalui Berkas CSV
     * Mendukung format header otomatis (Nama, Brand, Harga, RAM, Storage, dll.)
     * maupun format kolom standar 9 kolom berurutan.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $firstLine = fgets($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 2000, $delimiter);
        if (!$header) {
            fclose($handle);
            return redirect()->route('admin.dashboard', ['tab' => 'laptops'])
                ->with('error', 'Berkas CSV kosong atau tidak valid.');
        }

        // Petakan header secara fleksibel (case-insensitive & strip simbol)
        $headerMap = [];
        foreach ($header as $idx => $col) {
            $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $col)));
            $headerMap[$cleaned] = $idx;
        }

        $findCol = function (array $keys) use ($headerMap) {
            foreach ($keys as $k) {
                $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $k)));
                if (isset($headerMap[$cleaned])) {
                    return $headerMap[$cleaned];
                }
            }
            return null;
        };

        $idxNama = $findCol(['nama', 'name', 'model', 'namalaptop', 'laptopname', 'tipe']);
        $idxMerek = $findCol(['brand', 'merek', 'merk', 'manufacturer']);
        $idxHarga = $findCol(['harga', 'hargarp', 'price', 'cost']);
        $idxRam = $findCol(['ram', 'ramgb', 'memory']);
        $idxStorage = $findCol(['storage', 'storagegb', 'penyimpanan', 'ssd', 'hdd']);
        $idxBaterai = $findCol(['baterai', 'battery', 'batteryhours']);
        $idxBerat = $findCol(['berat', 'weight', 'weightkg', 'bobot']);
        $idxKondisi = $findCol(['kondisi', 'condition', 'status']);
        $idxPerforma = $findCol(['performakomposit', 'performa', 'performascore']);
        $idxProcessor = $findCol(['processor', 'cpu', 'prosessor']);
        $idxGpu = $findCol(['vga', 'gpu', 'graphic', 'grafis']);

        $hasSmartHeaders = ($idxNama !== null && ($idxHarga !== null || $idxRam !== null));
        $importedCount = 0;

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            if ($hasSmartHeaders) {
                $nama = trim($row[$idxNama] ?? '');
                if (empty($nama)) {
                    continue;
                }

                $merek = $idxMerek !== null ? trim($row[$idxMerek] ?? '') : '';
                if (empty($merek)) {
                    $parts = explode(' ', $nama);
                    $merek = $parts[0] ?? 'Generic';
                }

                // Parse Harga
                $rawHarga = $idxHarga !== null ? trim($row[$idxHarga] ?? '0') : '0';
                $hargaDigits = (int) preg_replace('/[^0-9]/', '', $rawHarga);
                if ($hargaDigits < 1000 && $hargaDigits > 0) {
                    $hargaDigits = (int) ($hargaDigits * 1000000);
                }
                $harga = $hargaDigits ?: 7000000;

                // Parse RAM
                $rawRam = $idxRam !== null ? trim($row[$idxRam] ?? '8') : '8';
                $ram = (int) preg_replace('/[^0-9]/', '', $rawRam) ?: 8;

                // Parse Storage (handle TB & GB)
                $rawStorage = $idxStorage !== null ? trim($row[$idxStorage] ?? '512') : '512';
                $storageVal = (int) preg_replace('/[^0-9]/', '', $rawStorage) ?: 512;
                if (stripos($rawStorage, 'tb') !== false && $storageVal < 10) {
                    $storage = $storageVal * 1024;
                } else {
                    $storage = $storageVal;
                }

                // Parse Baterai
                $rawBaterai = $idxBaterai !== null ? trim($row[$idxBaterai] ?? '6.0') : '6.0';
                $baterai = (float) str_replace(',', '.', trim(preg_replace('/[^0-9.,]/', '', $rawBaterai))) ?: 6.0;
                if ($baterai <= 0 || $baterai > 24) {
                    $baterai = 6.0;
                }

                // Parse Berat
                $rawBerat = $idxBerat !== null ? trim($row[$idxBerat] ?? '1.6') : '1.6';
                $berat = (float) str_replace(',', '.', trim(preg_replace('/[^0-9.,]/', '', $rawBerat))) ?: 1.6;
                if ($berat <= 0 || $berat > 10) {
                    $berat = 1.6;
                }

                // Parse Kondisi
                $rawKondisi = $idxKondisi !== null ? strtolower(trim($row[$idxKondisi] ?? 'baru')) : 'baru';
                $kondisi = (str_contains($rawKondisi, 'second') || str_contains($rawKondisi, 'bekas')) ? 'second' : 'baru';

                // Estimasi Performa Komposit
                if ($idxPerforma !== null && !empty($row[$idxPerforma])) {
                    $performa = (int) preg_replace('/[^0-9]/', '', $row[$idxPerforma]) ?: 65;
                } else {
                    $procText = strtolower($idxProcessor !== null ? ($row[$idxProcessor] ?? '') : $nama);
                    $gpuText = strtolower($idxGpu !== null ? ($row[$idxGpu] ?? '') : $nama);

                    $cpuScore = 60;
                    if (str_contains($procText, 'i9') || str_contains($procText, 'ryzen 9')) {
                        $cpuScore = 92;
                    } elseif (str_contains($procText, 'i7') || str_contains($procText, 'ryzen 7')) {
                        $cpuScore = 85;
                    } elseif (str_contains($procText, 'i5') || str_contains($procText, 'ryzen 5') || str_contains($procText, 'core 5')) {
                        $cpuScore = 75;
                    } elseif (str_contains($procText, 'i3') || str_contains($procText, 'ryzen 3')) {
                        $cpuScore = 60;
                    } elseif (str_contains($procText, 'n100') || str_contains($procText, 'n150') || str_contains($procText, 'n4020') || str_contains($procText, 'celeron')) {
                        $cpuScore = 45;
                    }

                    $gpuScore = 55;
                    if (str_contains($gpuText, 'rtx 40') || str_contains($gpuText, 'rtx40')) {
                        $gpuScore = 90;
                    } elseif (str_contains($gpuText, 'rtx 30') || str_contains($gpuText, 'rtx30')) {
                        $gpuScore = 80;
                    } elseif (str_contains($gpuText, 'gtx') || str_contains($gpuText, 'radeon 6')) {
                        $gpuScore = 68;
                    } elseif (str_contains($gpuText, 'mx')) {
                        $gpuScore = 60;
                    }

                    $performa = (int) round(($cpuScore + $gpuScore) / 2);
                }
            } else {
                // Fallback: Format Posisi 9 Kolom Standar
                if (count($row) < 9) {
                    continue;
                }

                $nama = trim($row[0]);
                $merek = trim($row[1]);
                $harga = (int) preg_replace('/[^0-9]/', '', $row[2]);
                $performa = (int) preg_replace('/[^0-9]/', '', $row[3]);
                $ram = (int) preg_replace('/[^0-9]/', '', $row[4]);
                $storage = (int) preg_replace('/[^0-9]/', '', $row[5]);
                $baterai = (float) str_replace(',', '.', trim($row[6]));
                $berat = (float) str_replace(',', '.', trim($row[7]));
                $kondisi = strtolower(trim($row[8])) === 'second' ? 'second' : 'baru';

                if (empty($nama)) {
                    continue;
                }
            }

            Laptop::updateOrCreate(
                ['name' => $nama],
                [
                    'brand' => $merek,
                    'price' => $harga,
                    'performa_komposit' => $performa ?: 60,
                    'processor_score' => $performa ?: 60,
                    'vga_score' => $performa ?: 60,
                    'ram_gb' => $ram ?: 8,
                    'storage_gb' => $storage ?: 512,
                    'battery_hours' => $baterai ?: 6.0,
                    'weight_kg' => $berat ?: 1.6,
                    'condition' => $kondisi,
                ]
            );

            $importedCount++;
        }
        fclose($handle);

        return redirect()->route('admin.dashboard', ['tab' => 'laptops'])
            ->with('success', 'Berhasil mengimpor ' . $importedCount . ' data laptop dari CSV.');
    }
}
