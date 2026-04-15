<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
})->name('login');

Route::get('/register', function () {
    return inertia('Register');
})->name('register');

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

Route::inertia('/', 'Landing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');


Route::middleware(['auth', 'verified'])->group(function () {

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

});

Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
})->name('edit.profile');

require __DIR__.'/settings.php';