<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        //
    }
}
