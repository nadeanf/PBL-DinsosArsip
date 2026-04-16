<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Arsip; 
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

Route::get('/sampah', function () {
    return inertia('Sampah', [
        'title' => 'Sampah'
    ]);
});

Route::get('/edit-dokumen', function () {
    return inertia('EditDokumen', [
        'title' => 'EditDokumen'
    ]);
});

Route::get('/riwayat', function () {
    return inertia('Riwayat', ['title' => 'Riwayat']);
})->name('riwayat');

Route::post('/register', [AuthController::class, 'register']);

// LOGIN
Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
});

Route::get('/register', function () {
    return inertia('Register');
});

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

// GROUP UTAMA
Route::middleware(['auth', 'verified'])->group(function () {

    Route::inertia('dashboard', 'Dashboard', [
        'title' => 'Beranda'
    ])->name('dashboard');

    // CONNECT FILTER
    Route::get('daftar-arsip', function (Request $request) {

        return inertia('ListArsip', [
            'title' => 'Daftar Arsip',
            'data' => [],
            'filters' => [
                'search' => $request->search,
                'kategori' => $request->kategori,
                'tanggal' => $request->tanggal,
            ]
        ]);

    })->name('arsip');

    // 🔥 TAMBAHAN DARI DEY (DITAMBAH, BUKAN NGUBAH)
    Route::get('/unggah', function () {
        return inertia('Unggah', ['title' => 'Unggah']);
    })->name('unggah');

    Route::get('/unggah/aktif&inaktif', function () {
        return inertia('UnggahAktif', ['title' => 'Pilih Folder']);
    })->name('unggah.aktif');

    Route::get('/unggah/{folder}', function ($folder) {
        return inertia('UnggahVital', [
            'folder' => $folder 
        ]);
    })->name('unggah.valid');

    Route::get('/kelola-arsip', function () {
        return inertia('KelolaArsip', [
            'title' => 'Kelola Arsip Saya'
        ]);
    })->name('kelola.arsip');

    // PUNYA KAMU (TETAP)
    Route::get('arsip/{id}', function ($id) {

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

// dari branch kintan (tetap dipakai)
Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
});

require __DIR__.'/settings.php';