<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arsip;
use App\Models\Kategori;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RequestAkses;
use App\Models\DownloadLog;
use App\Models\RiwayatAkses;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
       $request->merge([
    'nomor' => strtolower(trim($request->nomor))
]);

$request->validate([
    'judul' => 'required|string',
    'nomor' => 'required|string|unique:arsip,nomor',
    'tahun' => 'required',
    'id_kategori' => 'required|exists:kategori,id',
    'status_akses' => 'required',
'files.*' => [
        'file',
        function ($attribute, $file, $fail) {
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeMB = $file->getSize() / 1024 / 1024;

            // dokumen 2MB
            $dokumen = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'];

            $gambar = ['jpg','jpeg','png','gif','webp','bmp'];

            // audio 25MB
            $audio = ['mp3','wav','ogg','flac','aac','wma','m4a','opus','alac','aiff','dsd','pcm'];

            // video 100MB
            $video = ['mp4','avi','mkv','mov','wmv','flv','mpeg'];

            if (in_array($ext, $dokumen) && $sizeMB > 2) {
                $fail("Dokumen maksimal 2MB");
            }

            if (in_array($ext, $gambar) && $sizeMB > 5) {
                $fail("Gambar maksimal 5MB");
            }

            if (in_array($ext, $audio) && $sizeMB > 25) {
                $fail("Audio maksimal 25MB");
            }

            if (in_array($ext, $video) && $sizeMB > 100) {
                $fail("Video maksimal 100MB");
            }
        }
    ]
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
           'bagian' => $request->status_akses === 'private'
            ? $request->bagian
            : null,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'status_approval' => 'pending'
        ]);

       if ($request->hasFile('files')) {

    $judul = Str::slug($request->judul);
    $kategoriModel = Kategori::find($request->id_kategori);
    $kategori = Str::slug($kategoriModel?->nama ?? 'umum');
    $tanggal = Carbon::now()->format('Y-m-d');

    foreach ($request->file('files') as $file) {

        $ext = $file->getClientOriginalExtension();

        $namaFile = "{$judul}-{$kategori}-{$tanggal}.{$ext}";
        $namaFile = uniqid() . '-' . $namaFile;

        $folder = 'arsip/' . $kategori;

        $path = $file->storeAs($folder, $namaFile, 'public');

        File::create([
            'arsip_id' => $arsip->id,
            'path_file' => $path,

            // 🔥 simpan nama BARU biar konsisten
            'nama_file' => $namaFile,

            'tipe_file' => strtolower($ext),
            'size' => $file->getSize()
        ]);
    }
}
        return redirect()->route('kelola.arsip');
    }

    public function update(Request $request, $id)
    {
        $arsip = Arsip::with('files')->findOrFail($id);

        if ($request->has('status_akses') && !$request->has('judul')) {
            $arsip->update([
                'status_akses' => $request->status_akses
            ]);
            return back();
        }

       $request->merge([
    'nomor' => strtolower(trim($request->nomor))
]);

$request->validate([
    'judul' => 'required|string',
    'nomor' => [
        'required',
        'string',
        Rule::unique('arsip', 'nomor')->ignore($id)
    ],
    'tahun' => 'required|integer',
    'id_kategori' => 'required|exists:kategori,id',
    'status_akses' => 'required',

    'files.*' => [
        'file',
        function ($attribute, $file, $fail) {
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeMB = $file->getSize() / 1024 / 1024;

            // dokumen 2MB
            $dokumen = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'];

            $gambar = ['jpg','jpeg','png','gif','webp','bmp'];

            // audio 25MB
            $audio = ['mp3','wav','ogg','flac','aac','wma','m4a','opus','alac','aiff','dsd','pcm'];

            // video 100MB
            $video = ['mp4','avi','mkv','mov','wmv','flv','mpeg'];

            if (in_array($ext, $dokumen) && $sizeMB > 2) {
                $fail("Dokumen maksimal 2MB");
            }

            if (in_array($ext, $gambar) && $sizeMB > 5) {
                $fail("Gambar maksimal 5MB");
            }

            if (in_array($ext, $audio) && $sizeMB > 25) {
                $fail("Audio maksimal 25MB");
            }

            if (in_array($ext, $video) && $sizeMB > 100) {
                $fail("Video maksimal 100MB");
            }
        }
    ]
]);
        $jenisArsip = (now()->year - (int)$request->tahun >= 5)
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
            'jenis_arsip' => $jenisArsip,
            'bagian' => $request->status_akses === 'private'
    ? $request->bagian
    : null,
        ]);

      if ($request->hasFile('files')) {

    // 🔥 HAPUS FILE LAMA (DB + STORAGE)
    foreach ($arsip->files as $oldFile) {
        Storage::disk('public')->delete($oldFile->path_file);
        $oldFile->delete();
    }

    // upload file baru
    $judul = Str::slug($request->judul);
    $kategori = Str::slug(Kategori::find($request->id_kategori)?->nama ?? 'umum');
    $tanggal = Carbon::now()->format('Y-m-d');

    foreach ($request->file('files') as $file) {

        $ext = $file->getClientOriginalExtension();

        $namaFile = "{$judul}-{$kategori}-{$tanggal}.{$ext}";
        $namaFile = uniqid() . '-' . $namaFile;

        $folder = 'arsip/' . $kategori;

        $path = $file->storeAs($folder, $namaFile, 'public');

        File::create([
            'arsip_id' => $arsip->id,
            'path_file' => $path,
            'nama_file' => $namaFile,
            'tipe_file' => strtolower($ext),
            'size' => $file->getSize()
        ]);
    }
}
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
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('admin/KelolaArsipRoleAdmin', [
            'arsip' => $arsip
        ]);
    }

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

    public function landing()
{
    $arsip = Arsip::with(['kategori', 'user', 'files'])
        ->latest()
        ->take(3)
        ->get();

    $totalArsip = Arsip::count();

    return Inertia::render('Landing', [
        'arsip' => $arsip,
        'totalArsip' => $totalArsip
    ]);
}

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

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        $arsip = $query->latest()->get()->map(function ($item) {
            $user = Auth::user();
            $req = RequestAkses::where('user_id', $user->id)
                ->where('arsip_id', $item->id)
                ->first();

            $item->request_status = $req?->status;
             $item->request_user_id = $req?->user_id; 
            return $item;
        });

        $user = Auth::user();

        if ($user->role === 'pimpinan') {
            return Inertia::render('Pimpinan/ListArsipPimpinan', [
                'arsip' => $arsip,
                'kategori' => $this->kategoriTree(),
                'filters' => $request->only(['search', 'kategori', 'tanggal_awal', 'tanggal_akhir'])
            ]);
        }

        return Inertia::render('ListArsip', [
            'arsip' => $arsip,
            'kategori' => $this->kategoriTree(),
            'filters' => $request->only(['search', 'kategori', 'tanggal_awal', 'tanggal_akhir'])
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

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return Inertia::render('admin/ListArsipAdmin', [
            'arsip' => $query->latest()->get(),
            'kategori' => $this->kategoriTree(),
        
        'filters' => $request->only([
            'search',
            'kategori',
            'tanggal_awal',
            'tanggal_akhir'
        ]),
        ]);
    }

    public function edit($id)
    {
        $arsip = Arsip::with(['kategori', 'user'])->findOrFail($id);

        return Inertia::render('EditDokumen', [
            'arsip' => $arsip,
            'kategori' => Kategori::all()
        ]);
    }

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
            ->where('user_id', Auth::id())
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
            ->with(['files', 'user'])
            ->latest()
            ->get();

        return Inertia::render('admin/SampahAdmin', [
            'items' => $arsip
        ]);
    }

    public function dashboard(Request $request)
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
            ->take(3)
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
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->arsip?->judul ?? 'Arsip (dihapus)',
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

    public function dashboardSuperAdmin(Request $request)
{
    if (auth()->user()->role !== 'superadmin') {
        abort(403);
    }

    $query = Arsip::with(['kategori', 'user', 'files']);

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('nomor', 'like', '%' . $request->search . '%');
        });
    }

    // FILTER KATEGORI
    if ($request->kategori) {
        $query->where('jenis_arsip', $request->kategori);
    }

    // FILTER TANGGAL
    if ($request->tanggal_awal) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->tanggal_akhir) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $arsip = $query->latest()->get();

    // TOTAL VIEW
    $totalView = RiwayatAkses::where('aksi', 'lihat')->count();

    // TOTAL DOWNLOAD
    $totalDownload = DownloadLog::count();

    // TIPE DOKUMEN
    $tipeDokumen = File::selectRaw("
        CASE
            WHEN LOWER(nama_file) LIKE '%.jpg'
                OR LOWER(nama_file) LIKE '%.jpeg'
                OR LOWER(nama_file) LIKE '%.png'
            THEN 'Foto / Gambar'

            WHEN LOWER(nama_file) LIKE '%.pdf'
                OR LOWER(nama_file) LIKE '%.doc'
                OR LOWER(nama_file) LIKE '%.docx'
                OR LOWER(nama_file) LIKE '%.xls'
                OR LOWER(nama_file) LIKE '%.xlsx'
            THEN 'Dokumen'

            WHEN LOWER(nama_file) LIKE '%.mp4'
            THEN 'Video'

            WHEN LOWER(nama_file) LIKE '%.mp3'
            THEN 'Audio'

            ELSE 'Lainnya'
        END as nama,
        COUNT(*) as total
    ")
    ->groupBy('nama')
    ->get();

    return Inertia::render('SuperAdmin/DashboardSuperAdmin', [
        'arsip' => $arsip,
        'kategoriData' => $this->kategoriTree(),
        'totalView' => $totalView,
        'totalDownload' => $totalDownload,
        'tipeDokumen' => $tipeDokumen,
    ]);
}

    public function show($id)
    {
        $arsip = Arsip::with(['kategori', 'user', 'files'])->findOrFail($id);

        return Inertia::render('DetailArsip', [
            'arsip' => $arsip
        ]);
    }

    public function storeView(Request $request)
    {
        $userId = auth()->id();
        $arsipId = $request->dokumen_id;

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

        return response()->noContent();
    }

    public function download($id)
    {
        $arsip = Arsip::with('files')->findOrFail($id);

        if (!$this->canAccessFull($arsip)) {
            abort(403, 'Tidak punya akses');
        }

        $userId = auth()->id();

        $existingView = RiwayatAkses::where('user_id', $userId)
            ->where('arsip_id', $arsip->id)
            ->first();

        if ($existingView) {
            $existingView->update([
                'aksi' => 'download',
                'updated_at' => now()
            ]);
        } else {
            RiwayatAkses::create([
                'user_id' => $userId,
                'arsip_id' => $arsip->id,
                'aksi' => 'download'
            ]);
        }

        $file = $arsip->files->first();

        if (!$file || !Storage::disk('public')->exists($file->path_file)) {
            abort(404, 'File tidak ditemukan');
        }

        DownloadLog::create([
            'user_id' => Auth::id(),
            'arsip_id' => $arsip->id
        ]);

        return response()->download(
            storage_path('app/public/' . $file->path_file),
            $file->nama_file
        );
    }

    public function riwayat()
    {
        $data = RiwayatAkses::with('arsip.files', 'arsip.kategori', 'arsip.user')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get()
          ->map(function ($item) {

    $request = RequestAkses::where('user_id', Auth::id())
        ->where('arsip_id', $item->arsip->id)
        ->first();

    return [
        'id' => $item->arsip->id,
        'user_id' => $item->arsip->user_id,
        'title' => $item->arsip->judul,
        'nomor' => $item->arsip->nomor,
        'aksi' => $item->aksi,
        'deskripsi' => $item->arsip->deskripsi,
        'kategori' => $item->arsip->kategori->nama ?? '-',
        'jenis' => $item->arsip->jenis_arsip,
        'bidang' => $item->arsip->bagian ?? '-',
        'tahun' => $item->arsip->tahun,
        'lokasi' => $item->arsip->lokasi,

        // FIX
        'status_akses' => $item->arsip->status_akses,

        // FIX
        'request_status' => $request?->status,

        'files' => $item->arsip->files ?? [],
        'waktu' => $item->updated_at
    ];
});
        return Inertia::render('Riwayat', [
            'riwayat' => $data
        ]);
    }
public function riwayatAdmin()
{
    $data = RiwayatAkses::with('arsip.files', 'arsip.kategori', 'arsip.user')
        ->where('user_id', Auth::id())
        ->orderBy('updated_at', 'desc')
        ->get()

        ->filter(function ($item) {
            return $item->arsip !== null;
        })

        ->map(function ($item) {
            return [
                'id' => $item->arsip?->id,
                'user_id' => $item->arsip?->user_id,
                'title' => $item->arsip?->judul,
                'nomor' => $item->arsip?->nomor,
                'aksi' => $item->aksi,
                'deskripsi' => $item->arsip?->deskripsi,

                // FIX
                'kategori' => $item->arsip?->kategori?->nama ?? '-',

                'jenis' => $item->arsip?->jenis_arsip,

                // FIX
                'bidang' => $item->arsip?->user?->bagian ?? '-',

                'tahun' => $item->arsip?->tahun,
                'lokasi' => $item->arsip?->lokasi,
                'status' => $item->arsip?->status_akses,
                'files' => $item->arsip?->files ?? [],
                'waktu' => $item->updated_at
            ];
        });

    return Inertia::render('admin/RiwayatAdmin', [
    'riwayat' => $data->values()->all()
]);
}

    public function riwayatPimpinan()
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

        return Inertia::render('Pimpinan/RiwayatPimpinan', [
            'riwayat' => $data
        ]);
    }

  private function canAccessFull($arsip)
{
    $user = Auth::user();

    // 🔥 ADMIN & SUPERADMIN AKSES SEMUA
    if (in_array($user->role, ['admin', 'superadmin'])) {
        return true;
    }

    // publik bebas
    if ($arsip->status_akses === 'publik') return true;

    // pemilik arsip
    if ($arsip->user_id === $user->id) return true;

    // bidang sama
    if (
        strtolower(trim($arsip->bagian ?? '')) ===
        strtolower(trim($user->bagian ?? ''))
    ) {
        return true;
    }

    // approved
    $approved = RequestAkses::where('user_id', $user->id)
        ->where('arsip_id', $arsip->id)
        ->where('status', 'approved')
        ->exists();

    return $approved;
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
public function persetujuan(Request $request)
{
    if (auth()->user()->role !== 'admin') abort(403);

    $query = RequestAkses::with([
        'user:id,name,bagian',
        'arsip:id,judul,nomor,bagian'
    ]);

    // 🔍 SEARCH
    if ($request->search) {
    $query->whereHas('arsip', function ($q) use ($request) {
        $q->where('judul', 'like', $request->search . '%');
    });
}


    // 🎯 FILTER STATUS
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // ⚡ PRIORITAS: pending di atas
    $query->orderByRaw("status = 'pending' DESC")
          ->latest();

    // 🚀 PAGINATION (INI KUNCI NYA)
    $data = $query->paginate(10)->withQueryString();

    // format data
    $data->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'status' => $item->status,
            'created_at' => $item->created_at->format('d M Y'),
            'user' => $item->user,
            'arsip' => $item->arsip
        ];
    });

    return Inertia::render('admin/PersetujuanAkses', [
        'requests' => $data,
        'filters' => $request->only(['search', 'status'])
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
            ->get();

        return Inertia::render('admin/KelolaArsipUser', [
            'arsip' => $arsip
        ]);
    }
    /* SUPER ADMIN - KELOLA USER */
    public function kelolaUserSuperAdmin(Request $request)
{
    if (auth()->user()->role !== 'superadmin') abort(403);

    $query = User::query();

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('bagian', 'like', '%' . $request->search . '%');
        });
    }

    $users = $query->latest()->paginate(5)->withQueryString();

    return Inertia::render('SuperAdmin/KelolaUser', [
        'users' => $users,

        // STATISTIK
        'totalUser' => User::count(),
        'totalAktif' => User::where('is_active', true)->count(),
        'totalAdmin' => User::where('role', 'admin')->count(),

        // SEARCH VALUE
        'filters' => $request->only('search')
    ]);
}

public function exportPDF(Request $request)
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

    if ($request->filled('tanggal_awal')) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $arsip = $query->latest()->get()->map(function ($item) {
        return [
            'judul' => $item->judul,
            'nomor' => $item->nomor,
            'tahun' => $item->tahun,
            'kategori' => $item->kategori?->nama ?? '-',
            'status' => $item->status_akses,
            'download_url' => url('/download/' . $item->id),
        ];
    });

    $pdf = Pdf::loadView('pdf.laporan-arsip', [
        'data' => $arsip,
    ]);

    return $pdf->download('laporan-arsip.pdf');
}

public function statistikSuperAdmin(Request $request)
{
    if (auth()->user()->role !== 'superadmin') {
        abort(403);
    }

    // TOTAL FILE BERDASARKAN TIPE
    $dokumen = File::where(function ($q) {
        $q->where('nama_file', 'like', '%.pdf')
          ->orWhere('nama_file', 'like', '%.doc')
          ->orWhere('nama_file', 'like', '%.docx')
          ->orWhere('nama_file', 'like', '%.xls')
          ->orWhere('nama_file', 'like', '%.xlsx');
    })->count();

    $foto = File::where(function ($q) {
        $q->where('nama_file', 'like', '%.jpg')
          ->orWhere('nama_file', 'like', '%.jpeg')
          ->orWhere('nama_file', 'like', '%.png');
    })->count();

    $video = File::where('nama_file', 'like', '%.mp4')->count();

    $audio = File::where('nama_file', 'like', '%.mp3')->count();

    // TOTAL DOWNLOAD
    $download = DownloadLog::count();

    // TOTAL DILIHAT
    $dilihat = RiwayatAkses::where('aksi', 'lihat')->count();

    // USER TERAKTIF
    $users = User::withCount('arsip')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($user) {
            return [
                'nama' => $user->name,
                'role' => $user->role,
                'status' => $user->is_active ? 'Aktif' : 'Nonaktif',
                'tanggal' => $user->created_at->format('d M Y'),
                'dokumen' => $user->arsip_count
            ];
        });

    return Inertia::render('SuperAdmin/StatistikSuperAdmin', [
        'statistik' => [
            'dokumen' => $dokumen,
            'foto' => $foto,
            'video' => $video,
            'audio' => $audio,
            'download' => $download,
            'dilihat' => $dilihat,
        ],

        'users' => $users
    ]);

    
}
public function statistikAdmin()
{
    $totalArsip = Arsip::count();
    $totalDownload = DownloadLog::count();

    $tipeDokumen = File::selectRaw("
        CASE
            WHEN LOWER(nama_file) LIKE '%.jpg' 
                OR LOWER(nama_file) LIKE '%.jpeg'
                OR LOWER(nama_file) LIKE '%.png'
            THEN 'Foto / Gambar'

            WHEN LOWER(nama_file) LIKE '%.pdf'
                OR LOWER(nama_file) LIKE '%.doc'
                OR LOWER(nama_file) LIKE '%.docx'
                OR LOWER(nama_file) LIKE '%.xls'
                OR LOWER(nama_file) LIKE '%.xlsx'
            THEN 'Dokumen'

            WHEN LOWER(nama_file) LIKE '%.mp4'
            THEN 'Video'

            WHEN LOWER(nama_file) LIKE '%.mp3'
            THEN 'Audio'

            ELSE 'Lainnya'
        END as nama,
        COUNT(*) as total
    ")
    ->groupBy('nama')
    ->get();

    $kategoriStat = Arsip::selectRaw('kategori.nama as nama, COUNT(*) as total')
    ->join('kategori', 'arsip.id_kategori', '=', 'kategori.id')
    ->groupBy('kategori.nama')
    ->get();

    return Inertia::render('admin/StatistikLaporan', [
        'tipeDokumen' => $tipeDokumen,
        'totalArsip' => $totalArsip,
        'totalDownload' => $totalDownload,
        'kategoriStat' => $kategoriStat
    ]);
}

public function storeAdmin(Request $request)
{
    // copy isi store() kamu ke sini

    $request->merge([
    'nomor' => strtolower(trim($request->nomor))
]);

$request->validate([
    'judul' => 'required|string',
    'nomor' => 'required|string|unique:arsip,nomor',
    'tahun' => 'required',
    'id_kategori' => 'required|exists:kategori,id',
    'status_akses' => 'required',

   'files.*' => [
        'file',
        function ($attribute, $file, $fail) {
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeMB = $file->getSize() / 1024 / 1024;

            // dokumen 2MB
            $dokumen = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'];

            // gambar 5MB
            $gambar = ['jpg','jpeg','png','gif','webp','bmp'];

            // audio 25MB
            $audio = ['mp3','wav','ogg','flac','aac','wma','m4a','opus','alac','aiff','dsd','pcm'];

            // video 100MB
            $video = ['mp4','avi','mkv','mov','wmv','flv','mpeg'];

            if (in_array($ext, $dokumen) && $sizeMB > 2) {
                $fail("Dokumen maksimal 2MB");
            }

            if (in_array($ext, $gambar) && $sizeMB > 5) {
                $fail("Gambar maksimal 5MB");
            }

            if (in_array($ext, $audio) && $sizeMB > 25) {
                $fail("Audio maksimal 25MB");
            }

            if (in_array($ext, $video) && $sizeMB > 100) {
                $fail("Video maksimal 100MB");
            }
        }
    ]
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
        'bagian' => $request->status_akses === 'private'
        ? $request->bagian
        : null,
        'lokasi' => $request->lokasi,
        'deskripsi' => $request->deskripsi,
        'status_approval' => 'pending'
    ]);

    if ($request->hasFile('files')) {

    $judul = Str::slug($request->judul);
    $kategori = Str::slug(Kategori::find($request->id_kategori)?->nama ?? 'umum');
    $tanggal = Carbon::now()->format('Y-m-d');

    foreach ($request->file('files') as $file) {

        $ext = $file->getClientOriginalExtension();

        $namaFile = "{$judul}-{$kategori}-{$tanggal}.{$ext}";
        $namaFile = uniqid() . '-' . $namaFile;

        $folder = 'arsip/' . $kategori;

        $path = $file->storeAs($folder, $namaFile, 'public');

        File::create([
            'arsip_id' => $arsip->id,
            'path_file' => $path,
            'nama_file' => $namaFile, // 🔥 ini penting (bukan original lagi)
            'tipe_file' => strtolower($ext),
            'size' => $file->getSize()
        ]);
    }
}

    // 🔥 INI YANG PENTING
    return redirect('/admin/kelola-arsip-role-admin');
}
}
