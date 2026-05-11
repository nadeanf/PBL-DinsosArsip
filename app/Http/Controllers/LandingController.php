<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Arsip;
use App\Models\Pengumuman;

class LandingController extends Controller
{
    public function index()
    {
        $arsip = Arsip::with(['kategori', 'user', 'files'])
        ->where('status_akses', 'publik')
        ->latest()
        ->take(3)
        ->get();

        $pengumuman = Pengumuman::latest()
            ->take(3)
            ->get();

        return Inertia::render('Landing', [
            'arsip' => $arsip,
            'pengumuman' => $pengumuman,
            'totalArsip' => Arsip::count(),
        ]);
    }
}