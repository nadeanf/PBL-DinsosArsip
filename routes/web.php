<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\LandingController;
use App\Models\Kategori;

/* PUBLIC ROUTES */

Route::get('/arsipsaya', function () {
    return Inertia::render('ArsipSaya', ['title' => 'ArsipSaya']);
});

/* AUTH BASIC */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/* LOGIN */
Route::get('/login', function () {
    return Inertia::render('Login', ['status' => session('status')]);
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

/* PASSWORD RESET */
Route::get('/forgot-password', fn () => Inertia::render('auth/ForgotPassword'))->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

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

    Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return redirect('/login')->with('status', 'Password berhasil diubah!');
})->name('password.update');

/* LANDING */
Route::get('/', [LandingController::class, 'index'])
    ->name('home');

/* LOGOUT */
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

/* ROLE KHUSUS */
Route::middleware('auth')->group(function () {

    // PIMPINAN
    Route::get('/pimpinan/dashboard', [ArsipController::class, 'dashboardPimpinan']);

    Route::get('/pimpinan/statistik', function () {
        if (auth()->user()->role !== 'pimpinan') abort(403);
        return Inertia::render('Pimpinan/StatistikPimpinan', ['title' => 'Statistik']);
    });

    Route::get('/pimpinan/riwayat', [ArsipController::class, 'riwayatPimpinan']);

    Route::get('/pimpinan/daftar-arsip', [ArsipController::class, 'list']);

    // SUPER ADMIN
    Route::get('/super-admin/dashboard', [ArsipController::class, 'dashboardSuperAdmin']);

    Route::get('/super-admin/statistik', [ArsipController::class, 'statistikSuperAdmin']);

    Route::get('/super-admin/kelolauser', [ArsipController::class, 'kelolaUserSuperAdmin'])
    ->name('superadmin.kelolauser');

    Route::get('/super-admin/pengaturan', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return Inertia::render('SuperAdmin/Pengaturan');
    });

    Route::get('/super-admin/editstoragelimit', function () {
    if (auth()->user()->role !== 'superadmin') abort(403);
    return Inertia::render('SuperAdmin/EditStorageLimit');
    });
    
    Route::get('/super-admin/riwayat', function () {
    if (auth()->user()->role !== 'superadmin') abort(403);
    return Inertia::render('SuperAdmin/RiwayatSuperAdmin');
    });
    Route::patch('/super-admin/user/{id}/toggle', [UserController::class, 'toggleStatus']);
    Route::post('/super-admin/tambah-user', [UserController::class, 'store']);
    Route::get('/super-admin/daftar-arsip', [ArsipController::class, 'listAdmin']);
});
/* AUTH + FITUR */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/riwayat/view', [ArsipController::class, 'storeView']);
    Route::get('/sampah', [ArsipController::class, 'trash'])->name('arsip.trash');
    Route::get('/riwayat', [ArsipController::class, 'riwayat'])->name('riwayat');
    Route::get('/edit-dokumen/{id}', [ArsipController::class, 'edit'])->name('arsip.edit');

    Route::get('/dashboard', [ArsipController::class, 'dashboard'])->name('dashboard');

    // DOWNLOAD
    Route::get('/download/{id}', [ArsipController::class, 'download']);

    Route::post('/request-akses/{id}', [ArsipController::class, 'requestAkses'])
    ->name('request.akses');

    // STORE
    Route::post('/arsip', [ArsipController::class, 'store'])->name('arsip.store');

    //DETAIL ARSIP
    Route::get('/arsip/{id}', [ArsipController::class, 'show'])->name('arsip.show');

    // CRUD
    Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');
    Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
    Route::post('/arsip/{id}/restore', [ArsipController::class, 'restore'])->name('arsip.restore');
    Route::delete('/arsip/{id}/force', [ArsipController::class, 'forceDelete'])->name('arsip.force');

    // LIST
    Route::get('/daftar-arsip', [ArsipController::class, 'list'])
    ->name('arsip.index');

    // EXPORT
    Route::get('/export/pdf', [ArsipController::class, 'exportPDF'])->name('export.pdf');   

    // UPLOAD
    Route::get('/unggah', function () {
    return inertia('Unggah');
    })->name('unggah');

    // FORM UPLOAD
    Route::get('/unggah/{folder}', [ArsipController::class, 'create'])
        ->where('folder', 'vital|aktif-inaktif')
        ->name('unggah.valid');

    // KELOLA
    Route::get('/kelola-arsip', [ArsipController::class, 'index'])->name('kelola.arsip');


    /* ADMIN*/
    Route::get('/admin/dashboard', [ArsipController::class, 'dashboardAdmin']);

    Route::get('/admin/daftar-arsip', [ArsipController::class, 'listAdmin']);
    Route::get('/admin/persetujuan', [ArsipController::class, 'persetujuan'])
    ->name('admin.persetujuan');

    Route::post('/admin/persetujuan/{id}', [ArsipController::class, 'updatePersetujuan'])
    ->name('admin.persetujuan.update');

    Route::get('/admin/statistik', [ArsipController::class, 'statistikAdmin']);
    Route::get('/admin/kelola-arsip-user', [ArsipController::class, 'kelolaArsipUser']);

    // ADMIN
    Route::get('/admin/kelola-arsip-role-admin', [ArsipController::class, 'kelolaArsipAdmin']);

    Route::get('/admin/edit-dokumen/{id}', [ArsipController::class, 'editAdmin'])
    ->name('arsip.edit.admin');   

    Route::get('/admin/sampah-admin', [ArsipController::class, 'trashAdmin']);

    Route::get('/admin/riwayat', [ArsipController::class, 'riwayatAdmin']);

    Route::get('/admin/pengumuman', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return Inertia::render('admin/Pengumuman');
    });
    Route::post('/admin/pengumuman', [PengumumanController::class, 'store']);

    Route::get('/admin/kelola-kategori', [ArsipController::class, 'kelolaKategori'])
    ->middleware('auth');

    Route::post('/kategori', function (Illuminate\Http\Request $request) {

    if (auth()->user()->role !== 'admin') abort(403);

    $request->validate([
        'nama' => 'required|string',
        'parent_id' => 'nullable|exists:kategori,id'
    ]);

    \App\Models\Kategori::create([
        'nama' => $request->nama,
        'parent_id' => $request->parent_id
    ]);

    return back();
});

Route::delete('/kategori/{id}', function ($id) {

    if (auth()->user()->role !== 'admin') abort(403);

    \App\Models\Kategori::findOrFail($id)->delete();

    return back();
});

    Route::get('/admin/unggah/{folder}', [ArsipController::class, 'createAdmin']);

    Route::get('/admin/unggah/vital', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return Inertia::render('admin/UnggahVitalAdmin', [
            'folder' => 'vital',
        ]);
    });

    Route::get('/admin/UnggahAdmin', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return Inertia::render('admin/UnggahAdmin');
    });

    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.photo.update');

});

/* SETTINGS */
Route::get('/edit-profile', function () {
    return Inertia::render('settings/EditProfil'); 
})->name('edit.profile');

require __DIR__.'/settings.php';