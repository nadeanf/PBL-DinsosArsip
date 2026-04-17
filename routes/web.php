<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| AUTH BASIC
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
})->name('login');

Route::get('/register', function () {
    return inertia('Register');
})->name('register');

/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::inertia('/', 'Landing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

/*
|--------------------------------------------------------------------------
| AUTH AREA (USER + ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |---------------- USER ----------------|
    */

    Route::inertia('dashboard', 'Dashboard', [
        'title' => 'Beranda'
    ])->name('dashboard');

    Route::inertia('arsip', 'ListArsip', [
        'title' => 'Daftar Arsip'
    ])->name('arsip');

    Route::inertia('arsip-saya', 'ArsipSaya', [
        'title' => 'Arsip Saya'
    ])->name('arsip.saya');

    Route::inertia('detail-arsip', 'DetailArsip', [
        'title' => 'Detail Arsip'
    ])->name('arsip.detail');

    Route::inertia('riwayat', 'Riwayat', [
        'title' => 'Riwayat'
    ])->name('riwayat');

    Route::inertia('sampah', 'Sampah', [
        'title' => 'Sampah'
    ])->name('sampah');

    /*
    |---------------- UPLOAD ----------------|
    */

    Route::inertia('unggah', 'Unggah', [
        'title' => 'Unggah Dokumen'
    ])->name('unggah');

    Route::inertia('unggah-aktif', 'UnggahAktif', [
        'title' => 'Unggah Arsip Aktif'
    ])->name('unggah.aktif');

    Route::inertia('unggah-vital', 'UnggahVital', [
        'title' => 'Unggah Arsip Vital'
    ])->name('unggah.vital');

    /*
    |---------------- ADMIN ----------------|
    */

    Route::inertia('admin/dashboard', 'admin/Dashboard', [
        'title' => 'Dashboard Admin'
    ])->name('admin.dashboard');
     
    Route::inertia('admin/arsip-user', 'admin/KelolaArsipUser', [
    'title' => 'Kelola Arsip User'
    ])->name('admin.arsip-user');

    Route::inertia('admin/persetujuan', 'admin/PersetujuanAkses' , [
        'title' => 'Persetujuan Akses'
    ])->name('admin.persetujuan');

    Route::inertia('admin/unggah', 'admin/Unggah', [
    'title' => 'Unggah Dokumen Admin'
    ])->name('admin.unggah');

    Route::inertia('admin/unggah-aktif', 'admin/UnggahAktif', [
    'title' => 'Unggah Arsip Aktif Admin'
    ])->name('admin.unggah.aktif');

    Route::inertia('admin/unggah-vital', 'admin/UnggahVital', [
    'title' => 'Unggah Arsip Vital Admin'
    ])->name('admin.unggah.vital');

    Route::inertia('admin/riwayat', 'admin/Riwayat', [
        'title' => 'Riwayat Admin'
    ])->name('admin.riwayat');
});


/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/

Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
})->name('edit.profile');

require __DIR__.'/settings.php';