<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestionnaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'welcome'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/questionnaire', [QuestionnaireController::class, 'index'])->name('questionnaire');
    Route::post('/questionnaire', [QuestionnaireController::class, 'store'])->name('questionnaire.store');
    Route::get('/riwayat', [QuestionnaireController::class, 'history'])->name('questionnaire.history');
    Route::delete('/riwayat/{jawaban}', [QuestionnaireController::class, 'destroy'])->name('questionnaire.destroy');
    Route::get('/recommendation/{jawaban}', [QuestionnaireController::class, 'result'])->name('recommendation.result');
    Route::get('/recommendation/{jawaban}/pdf', [QuestionnaireController::class, 'pdf'])->name('recommendation.pdf');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/laptops', [AdminController::class, 'storeLaptop'])->name('admin.laptops.store');
    Route::put('/laptops/{laptop}', [AdminController::class, 'updateLaptop'])->name('admin.laptops.update');
    Route::delete('/laptops/{laptop}', [AdminController::class, 'destroyLaptop'])->name('admin.laptops.destroy');
    Route::post('/laptops/import', [AdminController::class, 'importCsv'])->name('admin.laptops.import');
    Route::post('/criteria/{kriteria}', [AdminController::class, 'updateCriteria'])->name('admin.criteria.update');
});
