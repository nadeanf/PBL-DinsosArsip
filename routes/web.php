<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Arsip; 
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use App\Http\Controllers\ArsipController;

/*
|---------------------------------------
| PUBLIC ROUTES
|---------------------------------------
*/

Route::post('/arsip', [ArsipController::class, 'store']);

Route::get('/sampah', function () {
    return inertia('Sampah', [
        'title' => 'Sampah'
    ]);
});

Route::get('/edit-dokumen/{id}', [ArsipController::class, 'edit'])
    ->name('arsip.edit');

Route::get('/riwayat', function () {
    return inertia('Riwayat', ['title' => 'Riwayat']);
})->name('riwayat');

Route::get('/arsipsaya', function () {
    return inertia('ArsipSaya', [
        'title' => 'ArsipSaya'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);

/*
| LOGIN
*/
Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
});

Route::get('/register', function () {
    return inertia('Register');
});

/*
| PASSWORD RESET
*/
Route::get('/forgot-password', function () {
    return Inertia::render('auth/ForgotPassword'); 
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return back()->with('status', __($status));
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return Inertia::render('auth/ResetPassword', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return redirect('/login')->with('status', 'Password berhasil diubah!');
})->name('password.update');

/*
| LANDING
*/
Route::inertia('/', 'Landing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

/*
| LOGOUT
*/
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');


/*
|---------------------------------------
| AUTH ROUTES
|---------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [ArsipController::class, 'dashboard'])
    ->name('dashboard');

    Route::put('/arsip/{id}', [ArsipController::class, 'update'])
        ->name('arsip.update');

    // ✅ FIX DI SINI (MASUKIN DELETE KE AUTH)
    Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])
        ->name('arsip.destroy');

    Route::post('/arsip/{id}/restore', [ArsipController::class, 'restore']);
    Route::delete('/arsip/{id}/force', [ArsipController::class, 'forceDelete']);

    // LIST ARSIP
    Route::get('/daftar-arsip', function (Request $request) {

        return inertia('ListArsip', [
            'title' => 'Daftar Arsip',
            'data' => [],
            'filters' => [
                'search' => $request->search,
                'kategori' => $request->kategori,
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]
        ]);

    })->name('arsip');

    // UPLOAD
    Route::inertia('/unggah', 'Unggah', [
        'title' => 'Unggah'
    ])->name('unggah');

    Route::get('/unggah/aktif&inaktif', function () {
        return redirect('/unggah');
    });

    Route::get('/unggah/{folder}', [ArsipController::class, 'create'])
        ->name('unggah.valid');

    // SIMPAN ARSIP
    Route::post('/arsip', [ArsipController::class, 'store']);

    // KELOLA ARSIP
    Route::get('/kelola-arsip', [ArsipController::class, 'index'])
        ->name('kelola.arsip');

    // DETAIL ARSIP
    Route::get('/arsip/{id}', function ($id) {

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


Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
});

// ❌ DIHAPUS DARI SINI (BIAR GA DOUBLE & GA 404)
// Route::delete('/arsip/{id}', ...)
// Route::post('/arsip/{id}/restore', ...)
// Route::delete('/arsip/{id}/force', ...)

Route::get('/sampah', [ArsipController::class, 'trash']);

require __DIR__.'/settings.php';