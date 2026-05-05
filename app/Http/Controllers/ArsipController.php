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
use App\Models\RiwayatAkses; //  TAMBAHAN

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
            'kategori' => Kategori::all()
        ]);
    }

    // 🔥 DASHBOARD (FIX ERROR KAMU)
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

        return Inertia::render('Dashboard', [
            'arsip' => $arsip,
            'kategori' => Kategori::all()
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

    public function update(Request $request, $id)
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
}

    public function trash()
{
    $arsip = Arsip::onlyTrashed()
        ->where('user_id', auth()->id())
        ->with(['kategori', 'files'])
        ->latest()
        ->get();

    return Inertia::render('Trash', [
        'items' => $arsip
    ]);
}

    private function canAccessFull($arsip)
    {
        $user = Auth::user();

        if ($arsip->status_akses === 'publik') return true;
        if ($arsip->user_id === $user->id) return true;

        return false;
    }
}