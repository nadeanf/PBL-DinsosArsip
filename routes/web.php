<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Arsip; // ⬅️ model nanti

Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);

// LOGIN
Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
});

// REGISTER 
Route::get('/register', function () {
    return inertia('Register');
});

Route::inertia('/', 'Landing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// ✅ GROUP UTAMA
Route::middleware(['auth', 'verified'])->group(function () {

    Route::inertia('dashboard', 'Dashboard', [
        'title' => 'Beranda'
    ])->name('dashboard');

    // 🔥 INI YANG UDAH CONNECT FILTER
    Route::get('daftar-arsip', function (Request $request) {

    return inertia('ListArsip', [
        'title' => 'Daftar Arsip',
        'data' => [], // dummy dulu
        'filters' => [
            'search' => $request->search,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
        ]
    ]);

})->name('arsip');

});

require __DIR__.'/settings.php';