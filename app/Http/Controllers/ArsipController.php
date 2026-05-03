<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arsip;
use App\Models\Kategori;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RequestAkses;
use App\Models\DownloadLog;


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

                File::create([
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

        $currentYear = now()->year;

        $jenisArsip = ($currentYear - (int)$request->tahun >= 5)
            ? 'inaktif'
            : 'aktif';

        $arsip->update([
            'judul' => $request->judul,
            'nomor' => $request->nomor,
            'tahun' => $request->tahun,
            'id_kategori' => $request->id_kategori,
            'status_akses' => $request->status_akses,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'jenis_arsip' => $jenisArsip
        ]);

        // HANDLE FILE BARU
        if ($request->hasFile('files')) {

            // hapus file lama
            foreach ($arsip->files as $old) {
                Storage::disk('public')->delete($old->path_file);
                $old->delete();
            }

            // simpan file baru
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

    // LIST ARSIP (KELOLA ARSIP)
    public function index()
    {
        $arsip = Arsip::with(['kategori', 'user', 'files'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $requestAkses = RequestAkses::where('user_id', auth()->id())->get();

        return Inertia::render('KelolaArsip', [
            'title' => 'Kelola Arsip',
            'arsip' => $arsip,
            'requestAkses' => $requestAkses
        ]);
    }

    // LIST ARSIP (UNTUK HALAMAN DAFTAR ARSIP)
    public function list(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files']);

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('nomor', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->kategori) {
        $query->where('id_kategori', $request->kategori);
    }

    if ($request->tanggal_awal) {
        $query->where('tahun', '>=', date('Y', strtotime($request->tanggal_awal)));
    }

    if ($request->tanggal_akhir) {
        $query->where('tahun', '<=', date('Y', strtotime($request->tanggal_akhir)));
    }
$arsip = $query->latest()->get()->map(function ($item) {
    $user = Auth::user();

    $req = RequestAkses::where('user_id', $user->id)
        ->where('arsip_id', $item->id)
        ->first();

    $item->request_status = $req?->status; // 🔥 INI WAJIB

    return $item;
});

    //hmch coba
    $user = Auth::user();

    if ($user->role === 'pimpinan') {
        return Inertia::render('Pimpinan/ListArsipPimpinan', [
            'arsip' => $arsip,
            'kategori' => Kategori::with('childrenRecursive')
                ->whereNull('parent_id')
                ->get(),
            'filters' => $request->only([
                'search',
                'kategori',
                'tanggal_awal',
                'tanggal_akhir'
            ])
        ]);
    }

    return Inertia::render('ListArsip', [
        'arsip' => $arsip,
        'kategori' => Kategori::with('childrenRecursive')
            ->whereNull('parent_id')
            ->get(),
        'filters' => $request->only([
            'search',
            'kategori',
            'tanggal_awal',
            'tanggal_akhir'
        ])
    ]);
}

    // EDIT PAGE
    public function edit($id)
    {
        $arsip = Arsip::with(['kategori', 'user'])->findOrFail($id);

        return Inertia::render('EditDokumen', [
            'arsip' => $arsip,
            'kategori' => Kategori::with('childrenRecursive')
                ->whereNull('parent_id')
                ->get(),
        ]);
    }

    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);
        $arsip->delete();

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
//now
    public function dashboard(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files']);

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('nomor', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->kategori) {
        $query->where('id_kategori', $request->kategori);
    }

    if ($request->tanggal_awal) {
        $query->where('tahun', '>=', date('Y', strtotime($request->tanggal_awal)));
    }

    if ($request->tanggal_akhir) {
        $query->where('tahun', '<=', date('Y', strtotime($request->tanggal_akhir)));
    }

    $arsip = $query->latest()->get()->map(function ($item) {
    $user = Auth::user();

    $req = RequestAkses::where('user_id', $user->id)
        ->where('arsip_id', $item->id)
        ->first();

    $item->request_status = $req?->status; // 🔥 INI WAJIB

    return $item;
});

    $totalDownload = DownloadLog::where('user_id', Auth::id())->count();

    return Inertia::render('Dashboard', [
        'arsip' => $arsip,
        'kategori' => Kategori::with('childrenRecursive')->get(),
        'totalDownload' => $totalDownload
    ]);
}

    public function show($id)
{
    $arsip = Arsip::with(['kategori', 'user', 'files'])->findOrFail($id);

    return Inertia::render('DetailArsip', [
        'arsip' => $arsip
    ]);
}

   public function download($id)
{
    $arsip = Arsip::with('files')->findOrFail($id);

    if (!$this->canAccessFull($arsip)) {
        abort(403, 'Tidak punya akses');
    }

    $file = $arsip->files->first();

    if (!$file || !Storage::disk('public')->exists($file->path_file)) {
        abort(404, 'File tidak ditemukan');
    }

    // log download
    DownloadLog::create([
        'user_id' => Auth::id(),
        'arsip_id' => $arsip->id
    ]);

    $filePath = storage_path('app/public/' . $file->path_file);

return response()->file($filePath, [
    'Content-Disposition' => 'attachment; filename="'.$file->nama_file.'"'
]);
}

    // RIWAYAT
    public function riwayat()
{
    $user = Auth::user();

    $data = Arsip::where('user_id', $user->id)
        ->latest()
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'aktivitas' => 'Mengunggah arsip',
                'waktu_aktivitas' => $item->created_at
            ];
        });

    // SWITCH VIEW BERDASARKAN ROLE
    if ($user->role === 'admin') {
        return Inertia::render('admin/RiwayatAdmin', [
            'riwayat' => $data
        ]);
    }

    if ($user->role === 'superadmin') {
        return Inertia::render('super-admin/RiwayatSuperAdmin', [
            'riwayat' => $data
        ]);
    }

    if ($user->role === 'pimpinan') {
        return Inertia::render('pimpinan/RiwayatPimpinan', [
            'riwayat' => $data
        ]);
    }

    // default user
    return Inertia::render('Riwayat', [
        'riwayat' => $data
    ]);
    }

public function exportPDF(Request $request)
{
    $search = $request->search;
    $kategori = $request->kategori;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $query = Arsip::with(['kategori', 'files']);

    if ($search) {
        $query->where('judul', 'like', "%$search%");
    }

    if ($kategori) {
        $query->where('id_kategori', $kategori);
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    }

    // mapping biar rapi + ada link download
    $data = $query->get()->map(function ($item) {

        $file = $item->files->first();

        return [
            'judul' => $item->judul,
            'nomor' => $item->nomor,
            'tahun' => $item->tahun,
            'kategori' => $item->kategori->nama ?? '-',
            'status' => $item->status_akses,

            // ini link download
            'download_url' => $file 
                ? url('/download/' . $item->id)
                : '-'
        ];
    });

    $pdf = Pdf::loadView('pdf.laporan-arsip', [
        'data' => $data
    ]);

    return $pdf->download('laporan_arsip.pdf');
}

private function canAccessFull($arsip)
{
    $user = Auth::user(); 

    $approved = RequestAkses::where('user_id', $user->id)
        ->where('arsip_id', $arsip->id)
        ->where('status', 'approved')
        ->exists();

    if ($approved) {
        return true;
    }

    if ($arsip->status_akses === 'publik') return true;

    if ($arsip->user_id === $user->id) return true;

    if ($arsip->status_akses === 'private' && $arsip->bagian === $user->bagian) return true;

    return false;
}
public function requestAkses($id)
{
    $user = Auth::user();

    RequestAkses::firstOrCreate([
        'user_id' => $user->id,
        'arsip_id' => $id
    ], [
        'status' => 'pending'
    ]);

    return back()->with('success', 'Request akses dikirim');
}
}