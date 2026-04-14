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


Route::get('/sampah', function () {
    return inertia('Sampah', [
        'title' => 'Sampah'
    ]);
});

Route::get('/riwayat', function () {
    return inertia('Riwayat', [
        'title' => 'Riwayat'
    ]);
});

Route::get('/arsipsaya', function () {
    return inertia('ArsipSaya', [
        'title' => 'ArsipSaya'
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

Route::get('arsip/{id}', function ($id) {

    // dummy dulu (nanti ganti dari DB)
    $documents = [
        [
            "id" => 0,
            "title" => "Proposal Program Pemberdayaan Masyarakat",
            "kategori" => "Proposal",
            "tanggal" => "25 Januari 2026",
            "status" => "Public",
            "file" => "/dummy.pdf"
        ]
    ];

    return inertia('DetailArsip', [
        'title' => 'Detail Arsip',
        'doc' => $documents[$id] ?? null
    ]);

})->name('arsip.detail');

});

require __DIR__.'/settings.php';