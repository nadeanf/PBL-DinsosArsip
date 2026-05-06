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
use App\Models\RiwayatAkses; //  TAMBAHAN

class ArsipController extends Controller
{
    public function create($folder)
    {
        return Inertia::render('UnggahAktif', [
            'folder' => $folder,
            'kategoriData' => $this->kategoriTree()
    ]);
    }

    public function createAdmin($folder)
    {
    return Inertia::render('admin/UnggahAktifAdmin', [
        'folder' => $folder,
        'kategoriData' => $this->kategoriTree()
    ]);
    }

    private function kategoriTree()
    {
        return Kategori::with('childrenRecursive')
            ->whereNull('parent_id')
            ->get();
    }

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
            $jenisArsip = ($currentYear - (int)$request->tahun >= 3)
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
                    'nama_file' => $file->getClientOriginalName(),
                    'tipe_file' => strtolower($file->getClientOriginalExtension()),
                    'size' => $file->getSize()
                ]);
            }
        }

        return redirect()->route('kelola.arsip');
    }

  public function update(Request $request, $id)
{
    $arsip = Arsip::with('files')->findOrFail($id);

    // ✅ KHUSUS UPDATE STATUS (ADMIN ONLY - QUICK UPDATE)
    if ($request->has('status_akses') && !$request->has('judul')) {
        $arsip->update([
            'status_akses' => $request->status_akses
        ]);

        return back();
    }

    // ✅ VALIDASI
    $request->validate([
        'judul' => 'required|string',
        'nomor' => 'nullable|string',
        'tahun' => 'required|integer',
        'id_kategori' => 'required|exists:kategori,id',
        'status_akses' => 'required',
        'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,mp4|max:20480'
    ]);

    // ✅ HITUNG JENIS ARSIP
    $jenisArsip = (now()->year - (int)$request->tahun >= 5)
        ? 'inaktif'
        : 'aktif';

    // ✅ UPDATE DATA UTAMA
    $arsip->update([
        'judul' => $request->judul,
        'nomor' => $request->nomor,
        'tahun' => $request->tahun,
        'id_kategori' => $request->id_kategori,
        'status_akses' => $request->status_akses,
        'lokasi' => $request->lokasi,
        'deskripsi' => $request->deskripsi,
        'jenis_arsip' => $jenisArsip,
        'bagian' => $request->status_akses === 'private'
            ? auth()->user()->bagian
            : null
    ]);

    // ✅ HANDLE FILE UPLOAD (REPLACE FILE LAMA)
    if ($request->hasFile('files')) {

        // hapus file lama
        foreach ($arsip->files as $old) {
            Storage::disk('public')->delete($old->path_file);
            $old->delete();
        }

        // upload file baru
        foreach ($request->file('files') as $file) {
            $path = $file->store('arsip', 'public');

            File::create([
                'arsip_id' => $arsip->id,
                'path_file' => $path,
                'nama_file' => $file->getClientOriginalName(),
                'tipe_file' => strtolower($file->getClientOriginalExtension()),
                'size' => $file->getSize()
            ]);
        }
    }

    // ✅ REDIRECT SESUAI ROLE
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect('/admin/kelola-arsip-role-admin')
            ->with('success', 'Arsip berhasil diperbarui');
    }

    return redirect('/kelola-arsip')
        ->with('success', 'Arsip berhasil diperbarui');
}

    public function kelolaArsipAdmin()
{
    if (auth()->user()->role !== 'admin') abort(403);

    $arsip = Arsip::with(['kategori', 'user', 'files'])
        ->where('user_id', Auth::id()) // ✅ INI KUNCINYA
        ->latest()
        ->get();
        

    return Inertia::render('admin/KelolaArsipRoleAdmin', [
        'arsip' => $arsip
    ]);
}

    // LIST ARSIP (UNTUK HALAMAN DAFTAR ARSIP)
    public function list(Request $request)
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

            $item->request_status = $req?->status;
            return $item;
        });

        $user = Auth::user();

        if ($user->role === 'pimpinan') {
            return Inertia::render('Pimpinan/ListArsipPimpinan', [
                'arsip' => $arsip,
                'kategori' => $this->kategoriTree(),
                'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
            ]);
        }

        return Inertia::render('ListArsip', [
            'arsip' => $arsip,
            'kategori' => $this->kategoriTree(),
            'filters' => $request->only(['search','kategori','tanggal_awal','tanggal_akhir'])
        ]);
    }

    public function listAdmin(Request $request)
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

        return Inertia::render('admin/ListArsipAdmin', [
            'arsip' => $query->latest()->get(),
            'kategori' => $this->kategoriTree(),
        ]);
    }

    /*public function edit($id)
    {
        
    }*/
    // LIST ARSIP SAYA
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

    // 🔥 LIST SEMUA ARSIP
    /*public function list(Request $request)
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

        $arsip = $query->latest()->get()->map(function ($item) {
            $user = Auth::user();

            $req = RequestAkses::where('user_id', $user->id)
                ->where('arsip_id', $item->id)
                ->first();

            $item->request_status = $req?->status;

            return $item;
        });

        return Inertia::render('ListArsip', [
            'arsip' => $arsip,
            'kategori' => $this->kategoriTree(),
        ]);
    }*/

    public function editAdmin($id)
    {
    if (auth()->user()->role !== 'admin') abort(403);

    $arsip = Arsip::with(['kategori', 'user'])->findOrFail($id);

    return Inertia::render('admin/EditDokumenAdmin', [
        'arsip' => $arsip,
        'kategori' => $this->kategoriTree(),
    ]);
    }

    public function destroy($id)
    {
        Arsip::findOrFail($id)->delete();
        return back();
    }

    public function restore($id)
    {
        Arsip::onlyTrashed()->findOrFail($id)->restore();
        return back();
    }

    public function forceDelete($id)
    {
        Arsip::onlyTrashed()->findOrFail($id)->forceDelete();
        return back();
    }

    public function trash()
{
    $arsip = Arsip::onlyTrashed()
        ->where('user_id', Auth::id()) // ✅ cuma milik sendiri
        ->with('files')
        ->latest()
        ->get();

    return Inertia::render('Sampah', [
        'items' => $arsip
    ]);
}

    public function trashAdmin()
{
     $arsip = Arsip::onlyTrashed()
        ->with(['files', 'user']) // 🔥 tambahin user biar tau pemilik
        ->latest()
        ->get();

    return Inertia::render('admin/SampahAdmin', [
        'items' => $arsip
    ]);
}

//now
            /*'kategori' => Kategori::all()
        ]);
    }*/

    // 🔥 DASHBOARD (FIX ERROR KAMU)
    public function dashboard(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files']);

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

        $item->request_status = $req?->status;

        return $item;
    });

    $totalDownload = DownloadLog::where('user_id', Auth::id())->count();

    return Inertia::render('Dashboard', [
        'arsip' => $arsip,
        'kategori' => $this->kategoriTree(),
        'totalDownload' => $totalDownload
    ]);
}

public function dashboardAdmin(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files']);

    $arsip = $query->latest()->get()->map(function ($item) {
        $user = Auth::user();

        $req = RequestAkses::where('user_id', $user->id)
            ->where('arsip_id', $item->id)
            ->first();

        $item->request_status = $req?->status;

        return $item;
    });

    $totalDownload = DownloadLog::where('user_id', Auth::id())->count();

    $totalArsip = Arsip::count();
    $tipeDokumen = File::selectRaw("
    CASE
        WHEN LOWER(nama_file) LIKE '%.jpg' 
            OR LOWER(nama_file) LIKE '%.jpeg'
            OR LOWER(nama_file) LIKE '%.png'
            OR LOWER(nama_file) LIKE '%.gif'
            OR LOWER(nama_file) LIKE '%.bmp'
        THEN 'Foto / Gambar'

        WHEN LOWER(nama_file) LIKE '%.pdf'
            OR LOWER(nama_file) LIKE '%.txt'
            OR LOWER(nama_file) LIKE '%.doc'
            OR LOWER(nama_file) LIKE '%.docx'
            OR LOWER(nama_file) LIKE '%.xls'
            OR LOWER(nama_file) LIKE '%.xlsx'
            OR LOWER(nama_file) LIKE '%.ppt'  
            OR LOWER(nama_file) LIKE '%.pptx'
        THEN 'Dokumen'

        WHEN LOWER(nama_file) LIKE '%.mp4'
            OR LOWER(nama_file) LIKE '%.avi'
            OR LOWER(nama_file) LIKE '%.mkv'
            OR LOWER(nama_file) LIKE '%.mov'
            OR LOWER(nama_file) LIKE '%.wmv'
            OR LOWER(nama_file) LIKE '%.flv'
            OR LOWER(nama_file) LIKE '%.mpeg'
        THEN 'Video'

        WHEN LOWER(nama_file) LIKE '%.mp3'
            OR LOWER(nama_file) LIKE '%.wav'
            OR LOWER(nama_file) LIKE '%.ogg'
            OR LOWER(nama_file) LIKE '%.flac'
            OR LOWER(nama_file) LIKE '%.aac'
            OR LOWER(nama_file) LIKE '%.wma'
            OR LOWER(nama_file) LIKE '%.m4a'
            OR LOWER(nama_file) LIKE '%.opus'
            OR LOWER(nama_file) LIKE '%.alac'
            OR LOWER(nama_file) LIKE '%.aiff'
            OR LOWER(nama_file) LIKE '%.dsd'
            OR LOWER(nama_file) LIKE '%.pcm'
        THEN 'Audio'

        ELSE 'Lainnya'
    END as nama,
    COUNT(*) as total
    ")
    ->groupBy('nama')
    ->get();

    $approvalList = RequestAkses::with(['user', 'arsip'])
    ->where('status', 'pending')
    ->latest()
    ->take(3) // ambil 3 data terbaru
    ->get()
    ->map(function ($item) {
        return [
            'title' => $item->arsip?->judul ?? 'Arsip (dihapus)',
            'user' => $item->user->name,
            'tanggal' => $item->created_at->format('d M Y'),
            'jumlah' => 1
        ];
    });
    
    $totalApproval = RequestAkses::where('status', 'pending')->count();

    return Inertia::render('admin/DashboardAdmin', [
    'arsip' => $arsip,
    'kategori' => $this->kategoriTree(),
    'totalDownload' => $totalDownload,
    'tipeDokumen' => $tipeDokumen,
    'approvalList' => $approvalList,
    'totalApproval' => $totalApproval,
    'totalArsip' => $totalArsip
    ]);
}

public function dashboardPimpinan(Request $request)
{
    $query = Arsip::with(['kategori', 'user', 'files']);

    $arsip = $query->latest()->get()->map(function ($item) {
        $user = Auth::user();

        $req = RequestAkses::where('user_id', $user->id)
            ->where('arsip_id', $item->id)
            ->first();

        $item->request_status = $req?->status;

        return $item;
    });

    $totalDownload = DownloadLog::where('user_id', Auth::id())->count();

    $totalArsip = Arsip::count();
    $tipeDokumen = File::selectRaw("
    CASE
        WHEN LOWER(nama_file) LIKE '%.jpg' 
            OR LOWER(nama_file) LIKE '%.jpeg'
            OR LOWER(nama_file) LIKE '%.png'
            OR LOWER(nama_file) LIKE '%.gif'
            OR LOWER(nama_file) LIKE '%.bmp'
        THEN 'Foto / Gambar'

        WHEN LOWER(nama_file) LIKE '%.pdf'
            OR LOWER(nama_file) LIKE '%.txt'
            OR LOWER(nama_file) LIKE '%.doc'
            OR LOWER(nama_file) LIKE '%.docx'
            OR LOWER(nama_file) LIKE '%.xls'
            OR LOWER(nama_file) LIKE '%.xlsx'
            OR LOWER(nama_file) LIKE '%.ppt'  
            OR LOWER(nama_file) LIKE '%.pptx'
        THEN 'Dokumen'

        WHEN LOWER(nama_file) LIKE '%.mp4'
            OR LOWER(nama_file) LIKE '%.avi'
            OR LOWER(nama_file) LIKE '%.mkv'
            OR LOWER(nama_file) LIKE '%.mov'
            OR LOWER(nama_file) LIKE '%.wmv'
            OR LOWER(nama_file) LIKE '%.flv'
            OR LOWER(nama_file) LIKE '%.mpeg'
        THEN 'Video'

        WHEN LOWER(nama_file) LIKE '%.mp3'
            OR LOWER(nama_file) LIKE '%.wav'
            OR LOWER(nama_file) LIKE '%.ogg'
            OR LOWER(nama_file) LIKE '%.flac'
            OR LOWER(nama_file) LIKE '%.aac'
            OR LOWER(nama_file) LIKE '%.wma'
            OR LOWER(nama_file) LIKE '%.m4a'
            OR LOWER(nama_file) LIKE '%.opus'
            OR LOWER(nama_file) LIKE '%.alac'
            OR LOWER(nama_file) LIKE '%.aiff'
            OR LOWER(nama_file) LIKE '%.dsd'
            OR LOWER(nama_file) LIKE '%.pcm'
        THEN 'Audio'

        ELSE 'Lainnya'
    END as nama,
    COUNT(*) as total
    ")
    ->groupBy('nama')
    ->get();

    $approvalList = RequestAkses::with(['user', 'arsip'])
    ->where('status', 'pending')
    ->latest()
    ->take(3) // ambil 3 data terbaru
    ->get()
    ->map(function ($item) {
        return [
            'title' => $item->arsip->judul,
            'user' => $item->user->name,
            'tanggal' => $item->created_at->format('d M Y'),
            'jumlah' => 1
        ];
    });

    return Inertia::render('Pimpinan/DashboardPimpinan', [
    'arsip' => $arsip,
    'kategori' => $this->kategoriTree(),
    'totalDownload' => $totalDownload,
    'tipeDokumen' => $tipeDokumen,
    'totalArsip' => $totalArsip
    ]);
}

    // DETAIL + CATAT RIWAYAT
    public function show($id)
{
    $arsip = Arsip::with(['kategori', 'user', 'files'])->findOrFail($id);

    //  SIMPAN RIWAYAT (INI PUNYAMU, JANGAN DIGANTI)
    RiwayatAkses::updateOrCreate(
    [
        'user_id' => Auth::id(),
        'arsip_id' => $arsip->id,
    ],
    [
        'aksi' => 'lihat',
        'updated_at' => now()
    ]
);
    //  BALIK LAGI KE HALAMAN SEBELUMNYA
    return back();
}
    public function storeView(Request $request)
    {
        $userId = auth()->id();
        $arsipId = $request->dokumen_id;

        // Cek apakah sudah ada aksi 'lihat'
        $existingView = RiwayatAkses::where('user_id', $userId)
            ->where('arsip_id', $arsipId)
            ->where('aksi', 'lihat')
            ->first();

        if ($existingView) {
            // Update waktu jika sudah ada
            $existingView->touch();
        } else {
            // Tambahkan entri baru untuk aksi 'lihat'
            RiwayatAkses::create([
                'user_id' => $userId,
                'arsip_id' => $arsipId,
                'aksi' => 'lihat'
            ]);
        }

        return response()->noContent();
    }

   /*public function download($id)
{
    $arsip = Arsip::with('files')->findOrFail($id);

    $userId = Auth::id();
    $arsipId = $arsip->id;

    // cek riwayat lihat
    $existingView = RiwayatAkses::where('user_id', $userId)
        ->where('arsip_id', $arsipId)
        ->where('aksi', 'lihat')
        ->first();

    if ($existingView) {
        $existingView->touch();
    } else {
        RiwayatAkses::create([
            'user_id' => $userId,
            'arsip_id' => $arsipId,
            'aksi' => 'lihat'
        ]);
    }

    $file = $arsip->files->first();

    if (!$file || !Storage::disk('public')->exists($file->path_file)) {
        abort(404, 'File tidak ditemukan');
    }

    DownloadLog::create([
        'user_id' => $userId,
        'arsip_id' => $arsipId
    ]);

    $filePath = storage_path('app/public/' . $file->path_file);

    return response()->file($filePath, [
        'Content-Disposition' => 'attachment; filename="'.$file->nama_file.'"'
    ]);
}

    // RIWAYAT
    //  DOWNLOAD + CATAT RIWAYAT
    public function download($id)
    {
        $arsip = Arsip::with('files')->findOrFail($id);

        if (!$this->canAccessFull($arsip)) {
            abort(403);
        }

        $userId = auth()->id();

        // Cek apakah sudah ada aksi 'lihat' untuk arsip ini
        $existingView = RiwayatAkses::where('user_id', $userId)
            ->where('arsip_id', $arsip->id)
            ->first();

        if ($existingView) {
            // Jika ada aksi 'lihat', ubah menjadi 'download'
            $existingView->update([
                'aksi' => 'download',
                'updated_at' => now()
            ]);
        } else if($existingView?->aksi !== 'download') {
            // Jika tidak ada, tambahkan entri baru untuk 'download'
            RiwayatAkses::create([
                'user_id' => $userId,
                'arsip_id' => $arsip->id,
                'aksi' => 'download'
            ]);
        }

        $file = $arsip->files->first();

        return response()->download(
            storage_path('app/public/' . $file->path_file),
            $file->nama_file
        );
    }*/

    public function download($id)
{
    $arsip = Arsip::with('files')->findOrFail($id);

    if (!$this->canAccessFull($arsip)) {
        abort(403);
    }

    $userId = auth()->id();
    $arsipId = $arsip->id;

    $existingView = RiwayatAkses::where('user_id', $userId)
        ->where('arsip_id', $arsipId)
        ->first();

    if ($existingView) {
        $existingView->update([
            'aksi' => 'download',
            'updated_at' => now()
        ]);
    } else {
        RiwayatAkses::create([
        'user_id' => $userId,
        'arsip_id' => $arsipId,
        'aksi' => 'download'
    ]);
    }

    $file = $arsip->files->first();

    if (!$file || !Storage::disk('public')->exists($file->path_file)) {
        abort(404, 'File tidak ditemukan');
    }

    DownloadLog::create([
        'user_id' => $userId,
        'arsip_id' => $arsipId
    ]);

    return response()->download(
        storage_path('app/public/' . $file->path_file),
        $file->nama_file
    );
}

    // RIWAYAT AKSES (FIX SESUAI LOGIKA KAMU)
    public function riwayat()
    {
        $data = RiwayatAkses::with('arsip.files', 'arsip.kategori', 'arsip.user')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
    'id' => $item->arsip->id,
    'user_id' => $item->arsip->user_id,

    'title' => $item->arsip->judul,
    'nomor' => $item->arsip->nomor,
    'aksi' => $item->aksi,
    'deskripsi' => $item->arsip->deskripsi,

    'kategori' => $item->arsip->kategori->nama ?? '-',
    'jenis' => $item->arsip->jenis_arsip,
    'bidang' => $item->arsip->user->bagian ?? '-',

    'tahun' => $item->arsip->tahun,
           'lokasi' => $item->arsip->lokasi,

           'status' => $item->arsip->status_akses,

             'files' => $item->arsip->files ?? [],

             'waktu' => $item->updated_at
];
            });

        return Inertia::render('Riwayat', [
            'riwayat' => $data
        ]);
    }
    // EDIT PAGE
    public function edit($id)
    {
    $arsip = Arsip::with('kategori', 'files')->findOrFail($id);

    return Inertia::render('EditDokumen', [
        'arsip' => $arsip,
        'kategori' => Kategori::all()
    ]);
    }

    /*public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required|string',
        'tahun' => 'required',
        'id_kategori' => 'required|exists:kategori,id',
        'status_akses' => 'required'
    ]);

    $arsip = Arsip::findOrFail($id);

    $arsip->update([
        'judul' => $request->judul,
        'nomor' => $request->nomor,
        'tahun' => $request->tahun,
        'id_kategori' => $request->id_kategori,
        'status_akses' => $request->status_akses,
        'bagian' => $request->status_akses === 'private' ? Auth::user()->bagian : null,
        'lokasi' => $request->lokasi,
        'deskripsi' => $request->deskripsi,
    ]);

    return redirect()->route('kelola.arsip');
}*/

    /*public function trash()
{
    $arsip = Arsip::onlyTrashed()
        ->where('user_id', Auth::id())
        ->with(['kategori', 'files'])
        ->latest()
        ->get();

    return Inertia::render('Sampah', [
        'items' => $arsip
    ]);
}*/

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

/*private function canAccessFull($arsip)
{
    $user = Auth::user(); 
}*/

    private function canAccessFull($arsip)
{
    $user = Auth::user();

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

    public function kelolaKategori()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        return Inertia::render('admin/KelolaKategori', [
            'kategori' => $this->kategoriTree(),
            'vital' => Kategori::where('nama', 'VITAL')
                ->with('childrenRecursive')
                ->first()?->children ?? []
        ]);
    }
public function persetujuan()
{
    if (auth()->user()->role !== 'admin') abort(403);

    $data = RequestAkses::with([
    'user:id,name,bagian',
    'arsip:id,judul,nomor,bagian'
])
    ->latest()
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'status' => $item->status,
            'created_at' => $item->created_at->format('d M Y'),
            'user' => $item->user,
            'arsip' => $item->arsip
        ];
    });

    return Inertia::render('admin/PersetujuanAkses', [
        'requests' => $data // ✅ WAJIB ini
    ]);
}

public function updatePersetujuan(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    $item = RequestAkses::findOrFail($id);
    $item->status = $request->status;
    $item->save();

    return back();
}
public function kelolaArsipUser()
{
    if (auth()->user()->role !== 'admin') abort(403);

    $arsip = Arsip::with(['kategori', 'user', 'files'])
        ->latest()
        ->get(); // ✅ semua arsip user

    return Inertia::render('admin/KelolaArsipUser', [
        'arsip' => $arsip
    ]);
}
/*public function index()
{
    $arsip = Arsip::with(['kategori', 'files'])
        ->where('user_id', Auth::id()) // ✅ hanya arsip milik user
        ->latest()
        ->get();

    return Inertia::render('KelolaArsip', [
        'arsip' => $arsip
    ]);
}*/
}