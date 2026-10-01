<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function welcome(): View
    {
        // Ambil laptop terbaik & terbaru: prioritaskan laptop berharga valid dan performa/rating tinggi
        $featuredLaptops = Laptop::whereNotNull('price')
            ->where('price', '>', 0)
            ->orderByDesc('performa_komposit')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        if ($featuredLaptops->isEmpty()) {
            $featuredLaptops = Laptop::take(8)->get();
        }

        // Ambil juga beberapa kategori unggulan (Gaming, Portabel, Budget hemat)
        $topGaming = Laptop::whereNotNull('price')
            ->where('price', '>', 0)
            ->where(function ($q) {
                $q->where('category', 'like', '%gaming%')
                  ->orWhere('name', 'like', '%tuf%')
                  ->orWhere('name', 'like', '%rog%')
                  ->orWhere('name', 'like', '%legion%')
                  ->orWhere('name', 'like', '%gaming%')
                  ->orWhere('name', 'like', '%nitro%');
            })
            ->orderByDesc('performa_komposit')
            ->take(4)
            ->get();

        $topPortable = Laptop::whereNotNull('price')
            ->where('price', '>', 0)
            ->where('weight_kg', '<=', 1.5)
            ->orderByDesc('battery_hours')
            ->take(4)
            ->get();

        $topBudget = Laptop::whereNotNull('price')
            ->where('price', '>', 0)
            ->where('price', '<=', 10000000)
            ->orderByDesc('performa_komposit')
            ->take(4)
            ->get();

        return view('welcome', compact('featuredLaptops', 'topGaming', 'topPortable', 'topBudget'));
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => 'mahasiswa',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil didaftarkan! Selamat datang di Sistem Rekomendasi Laptop.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended($user->role === 'admin' ? route('admin.dashboard') : route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
