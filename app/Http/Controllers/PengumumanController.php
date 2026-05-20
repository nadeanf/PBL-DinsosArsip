<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    return inertia('admin/Pengumuman', [
        'pengumuman' => Pengumuman::latest()->paginate(4),
        'trashed' => Pengumuman::onlyTrashed()->latest()->paginate(4)
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $path = null;

    // upload file
    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('pengumuman', 'public');
    }

    // simpan database
    Pengumuman::create([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'tanggal' => $request->tanggal,
        'file' => $path,
        'user_id' => auth()->id()
    ]);

    return back()->with('success', 'Pengumuman berhasil disimpan');

    $request->validate([
    'judul' => 'required|string',
    'deskripsi' => 'required|string',
    'tanggal' => 'required|date',
]);
}

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ];

        // Update file jika ada file baru
        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($pengumuman->file) {
                Storage::disk('public')->delete($pengumuman->file);
            }
            $data['file'] = $request->file('file')->store('pengumuman', 'public');
        }

        // Update data
        $pengumuman->update($data);

        return back()->with('success', 'Pengumuman berhasil diperbarui');

        $request->validate([
    'judul' => 'required|string',
    'deskripsi' => 'required|string',
    'tanggal' => 'required|date',
]);
    }

    /**
     * Soft delete the resource (move to trash).
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return back()->with('success', 'Pengumuman berhasil dipindahkan ke sampah');
    }

    /**
     * Restore from trash.
     */
   public function restore($pengumuman)
{
    $pengumuman = Pengumuman::withTrashed()->findOrFail($pengumuman);
        $pengumuman->restore();

        return back()->with('success', 'Pengumuman berhasil dipulihkan dari sampah');
    }

    /**
     * Permanently delete from trash.
     */
    public function permanentDelete($pengumuman)
{
    $pengumuman = Pengumuman::withTrashed()->findOrFail($pengumuman);

        // Hapus file jika ada
        if ($pengumuman->file) {
            Storage::disk('public')->delete($pengumuman->file);
        }

        $pengumuman->forceDelete();

        return back()->with('success', 'Pengumuman berhasil dihapus permanent');
    }
}
