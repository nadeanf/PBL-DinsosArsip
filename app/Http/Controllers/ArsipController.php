<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arsip;
use App\Models\Kategori;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class ArsipController extends Controller
{
    // UPLOAD PAGE
    public function create($folder)
    {
        return Inertia::render('UnggahAktif', [
            'folder' => $folder,
            'kategoriData' => Kategori::all()
        ]);
    }

    // SIMPAN ARSIP
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'tahun' => 'required',
            'id_kategori' => 'required|exists:kategori,id',
            'status_akses' => 'required'
        ]);

        $user = Auth::user();

        if ($request->folder === 'vital') {
            $jenisArsip = 'vital';
        } else {
            $currentYear = now()->year;
            $jenisArsip = ($currentYear - (int)$request->tahun >= 5)
                ? 'inaktif'
                : 'aktif';
        }

        $arsip = Arsip::create([
            
            'user_id' => $user->id,
            'judul' => $request->judul,
            'nomor' => $request->nomor,
            'tahun' => $request->tahun,
            'id_kategori' => $request->id_kategori,
            'jenis_arsip' => $jenisArsip,
            'status_akses' => $request->status_akses,
            'bagian' => $request->status_akses === 'private' ? $user->bagian : null,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'status_approval' => 'pending'
        ]);
         if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {

            $path = $file->store('arsip', 'public');

            \App\Models\File::create([
                'arsip_id' => $arsip->id,
                'path_file' => $path,
                'nama_file' => $file->getClientOriginalName()
            ]);
        }
    }
        return redirect()->route('kelola.arsip');
    }

    // UPDATE ARSIP (EDIT)
    public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required|string',
        'nomor' => 'nullable|string',
        'tahun' => 'required|integer',
        'id_kategori' => 'required|exists:kategori,id',
        'status_akses' => 'required'
    ]);

    $arsip = Arsip::with('files')->findOrFail($id);

    $arsip->update([
        'judul' => $request->judul,
        'nomor' => $request->nomor,
        'tahun' => $request->tahun,
        'id_kategori' => $request->id_kategori,
        'status_akses' => $request->status_akses,
        'lokasi' => $request->lokasi,
        'deskripsi' => $request->deskripsi,
    ]);

    // 🔥 HANDLE FILE BARU
    if ($request->hasFile('files')) {

        // 🧨 hapus file lama
        foreach ($arsip->files as $old) {
            Storage::disk('public')->delete($old->path_file);
            $old->delete();
        }

        // 🚀 simpan file baru
        foreach ($request->file('files') as $file) {
            $path = $file->store('arsip', 'public');

            File::create([
                'arsip_id' => $arsip->id,
                'path_file' => $path,
                'nama_file' => $file->getClientOriginalName()
            ]);
        }
    }

    return redirect()->route('kelola.arsip');
}
    // LIST ARSIP
    public function index()
    {
        $arsip = Arsip::with(['kategori', 'user', 'files'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('KelolaArsip', [
            'title' => 'Kelola Arsip',
            'arsip' => $arsip
        ]);
    }

    // EDIT PAGE
   public function edit($id)
{
    $arsip = Arsip::with(['kategori', 'user'])->findOrFail($id);

    return Inertia::render('EditDokumen', [
        'arsip' => $arsip,
        'kategori' => Kategori::all()
    ]);
}

    public function destroy($id)
{
    $arsip = Arsip::findOrFail($id);
    $arsip->delete(); // 🔥 masuk sampah (soft delete)

    return back();
}

public function restore($id)
{
    $arsip = Arsip::onlyTrashed()->findOrFail($id);
    $arsip->restore();

    return back();
}

public function forceDelete($id)
{
    $arsip = Arsip::onlyTrashed()->findOrFail($id);
    $arsip->forceDelete();

    return back();
}

public function trash()
{
    $arsip = Arsip::onlyTrashed()
        ->with('files')
        ->latest()
        ->get();

    return Inertia::render('Sampah', [
        'items' => $arsip
    ]);
}
public function dashboard(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files'])
        ->where(function ($q) {
            $q->where('status_akses', 'publik')
              ->orWhere('user_id', Auth::id());
        });

    // 🔍 SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('nomor', 'like', '%' . $request->search . '%');
        });
    }

    // 🗂️ FILTER KATEGORI
    if ($request->kategori) {
        $query->where('id_kategori', $request->kategori);
    }

    // 📅 FILTER TANGGAL (tahun)
    if ($request->tanggal_awal) {
        $query->where('tahun', '>=', date('Y', strtotime($request->tanggal_awal)));
    }

    if ($request->tanggal_akhir) {
        $query->where('tahun', '<=', date('Y', strtotime($request->tanggal_akhir)));
    }

    $arsip = $query->latest()->get();

    return Inertia::render('Dashboard', [
        'arsip' => $arsip,
        'kategori' => Kategori::all()
    ]);
}
}
