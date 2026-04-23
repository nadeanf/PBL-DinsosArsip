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

Route::get('/arsipsaya', function () {
    return inertia('ArsipSaya', [
        'title' => 'ArsipSaya'
    ]);
});


/* AUTH BASIC */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {
    return inertia('Login', [
        'status' => session('status'),
    ]);
})->name('login');

Route::get('/register', function () {
    return inertia('Register');
})->name('register');


/* FORGOT PASSWORD */
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


/* LANDING */
Route::inertia('/', 'Landing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');


/* LOGOUT */
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');


/* PIMPINAN */
Route::get('/pimpinan/dashboard', function () {
    if (auth()->user()->role !== 'pimpinan') abort(403);

    return inertia('Pimpinan/DashboardPimpinan', [
        'title' => 'Dashboard'
    ]);
})->middleware('auth');

Route::get('/pimpinan/statistik', function () {
    if (auth()->user()->role !== 'pimpinan') abort(403);

    return inertia('Pimpinan/StatistikPimpinan', [
        'title' => 'Statistik'
    ]);
})->middleware('auth');


/* SUPER ADMIN */
Route::middleware(['auth'])->group(function () {

    Route::get('/super-admin/dashboard', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/DashboardSuperAdmin', ['title' => 'Dashboard Super Admin']);
    });

    Route::get('/super-admin/statistik', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/StatistikSuperAdmin', ['title' => 'Statistik Sistem']);
    });

    Route::get('/super-admin/kelolauser', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/KelolaUser', ['title' => 'Kelola Pengguna']);
    });

    Route::get('/super-admin/riwayat', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/RiwayatSuperAdmin', ['title' => 'Riwayat']);
    })->name('superadmin.riwayat');

    Route::get('/super-admin/pengaturan', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/Pengaturan', ['title' => 'Pengaturan']);
    })->name('superadmin.pengaturan');

    Route::get('/super-admin/editstoragelimit', function () {
        if (auth()->user()->role !== 'superadmin') abort(403);
        return inertia('SuperAdmin/EditStorageLimit', ['title' => 'Edit Limit Penyimpanan']);
    })->name('superadmin.editlimit');
});


/* GROUP UTAMA */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::inertia('dashboard', 'Dashboard', [
        'title' => 'Beranda'
    ])->name('dashboard');


    /* RIWAYAT */
    Route::get('/riwayat', function () {
        $role = auth()->user()->role; 

        if ($role === 'pimpinan') {
            return inertia('Pimpinan/RiwayatPimpinan', ['title' => 'Riwayat']);
        }

        if ($role === 'admin') {
            return inertia('admin/RiwayatAdmin', ['title' => 'Riwayat']);
        }
        
        if ($role === 'superadmin') {
            return inertia('SuperAdmin/RiwayatSuperAdmin', ['title' => 'Riwayat']);
        }

        return inertia('Riwayat', ['title' => 'Riwayat']);
    })->name('riwayat');


    /* DAFTAR ARSIP */
    Route::get('daftar-arsip', function (Request $request) {

        $role = auth()->user()->role;

        if ($role === 'pimpinan') {
            return inertia('Pimpinan/ListArsipPimpinan', [
                'title' => 'Daftar Arsip',
                'data' => [],
                'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
            ]);
        }

        if ($role === 'admin') {
            return inertia('admin/ListArsipAdmin', [
                'title' => 'Daftar Arsip',
                'data' => [],
                'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
            ]);
        }

        if ($role === 'superadmin') {
            return inertia('SuperAdmin/ListArsipSuperAdmin', [
                'title' => 'Daftar Arsip',
                'data' => [],
                'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
            ]);
        }

        return inertia('ListArsip', [
            'title' => 'Daftar Arsip',
            'data' => [],
            'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
        ]);

    })->name('arsip');


   /* UNGGAH (ROLE) */
    // HALAMAN AWAL UNGGAH
    Route::get('/unggah', function () {

        $role = auth()->user()->role;

        if ($role === 'admin') {
            return inertia('admin/UnggahAdmin', [
                'title' => 'Unggah Arsip'
            ]);
        }

        return inertia('Unggah', [
            'title' => 'Unggah Arsip'
        ]);

    })->name('unggah');


    // PILIH FOLDER (AKTIF/INAKTIF)
    Route::get('/unggah/aktif-inaktif', function () {

        $role = auth()->user()->role;

        if ($role === 'admin') {
            return inertia('admin/UnggahAktifAdmin', [
                'title' => 'Pilih Folder'
            ]);
        }

        return inertia('UnggahAktif', [
            'title' => 'Pilih Folder'
        ]);

    })->name('unggah.aktif');


    // HALAMAN UNGGAH BERDASARKAN FOLDER
    Route::get('/unggah/{folder}', function ($folder) {

        // VALIDASI BIAR GA NGACO
        if (!in_array($folder, ['vital', 'aktif-inaktif'])) {
            abort(404);
        }

        $role = auth()->user()->role;

        // ADMIN
        if ($role === 'admin') {
            return inertia('admin/UnggahVitalAdmin', [
                'title' => 'Unggah Dokumen',
                'folder' => $folder
            ]);
        }

        // USER / DEFAULT
        return inertia('UnggahVital', [
            'title' => 'Unggah Dokumen',
            'folder' => $folder
        ]);

    })->name('unggah.valid');


    /* KELOLA ARSIP (ROLE) */
    Route::get('/kelola-arsip', function () {

        $role = auth()->user()->role;

        if ($role === 'admin') {
            return inertia('admin/KelolaArsipRoleAdmin', [
                'title' => 'Kelola Arsip Saya'
            ]);
        }

        return inertia('KelolaArsip', [
            'title' => 'Kelola Arsip Saya'
        ]);

    })->name('kelola.arsip');


    /* DETAIL ARSIP */
    Route::get('arsip/{id}', function ($id) {

        $doc = [
            "id" => 0,
            "title" => "Proposal Program Pemberdayaan Masyarakat",
            "nomor" => "DOK/012/11/2026",
            "jenis" => "Proposal",
            "tanggal" => "25 Januari 2026",
            "status" => "Public",
            "file" => "/dummy.pdf"
        ];

        $role = auth()->user()->role;

        if ($role === 'pimpinan') {
            return inertia('Pimpinan/DetailArsipPimpinan', ['title' => 'Detail Arsip', 'doc' => $doc]);
        }

        if ($role === 'admin') {
            return inertia('admin/DetailArsipAdmin', ['title' => 'Detail Arsip', 'doc' => $doc]);
        }

        if ($role === 'superadmin') {
            return inertia('SuperAdmin/DetailArsipSuperAdmin', ['title' => 'Detail Arsip', 'doc' => $doc]);
        }

        return inertia('DetailArsip', ['title' => 'Detail Arsip', 'doc' => $doc]);

    })->name('arsip.detail');


    /* ADMIN */
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return inertia('admin/DashboardAdmin', ['title' => 'Dashboard Admin']);
    });

    Route::get('/admin/statistik', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return inertia('admin/StatistikLaporan', ['title' => 'Statistik']);
    });

    Route::get('/admin/persetujuan', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return inertia('admin/PersetujuanAkses', ['title' => 'Persetujuan']);
    });

        Route::get('/admin/kelola-user', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return inertia('admin/KelolaArsipUser', ['title' => 'Kelola Arsip User']);
    });

        Route::get('/admin/pengumuman', function () {
        if (auth()->user()->role !== 'admin') abort(403);

        return inertia('admin/Pengumuman', [
            'title' => 'Pengumuman']);
    });

    /* KELOLA KATEGORI (ADMIN) */
    Route::get('/admin/kelola-kategori', function () {
    if (auth()->user()->role !== 'admin') abort(403);

    return inertia('admin/KelolaKategori', [
        'title' => 'Kelola Kategori']);
    });
});


/* SETTINGS */
Route::get('/edit-profile', function () {
    return inertia('settings/EditProfil', [
        'title' => 'Edit Profil'
    ]);
})->name('edit.profile');

require __DIR__.'/settings.php';