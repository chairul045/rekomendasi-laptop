<?php

namespace App\Http\Controllers;

use App\Models\KuisionerJawaban;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Periksa apakah pengguna sudah pernah mengisi kuisioner.
        // Jika belum, pengguna diwajibkan mengisi kuisioner kebutuhan sebelum dapat melihat rekomendasi.
        if ($user->role !== 'admin' && !$user->hasAnsweredQuestionnaire()) {
            return redirect()->route('questionnaire')
                ->with('info', 'Selamat datang! Anda diwajibkan mengisi kuisioner kebutuhan terlebih dahulu sebelum dapat melihat hasil rekomendasi laptop.');
        }

        $consultations = KuisionerJawaban::where('user_id', $user->id)
            ->with(['bobotHasil.kriteria', 'hasilTopsis.laptop'])
            ->orderByDesc('created_at')
            ->get();

        $latestJawaban = $consultations->first();

        return view('dashboard', compact('user', 'consultations', 'latestJawaban'));
    }
}
