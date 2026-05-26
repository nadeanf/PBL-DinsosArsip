<?php

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
         return response()->json($request->all());
        Kategori::create([
            'nama' => $request->nama,
            'masa_aktif' => 3, // default 3 tahun
            'parent_id' => $request->parent_id
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->update([
            'nama' => $request->nama,
             'masa_aktif' => $request->masa_aktif ?? 3
        ]);

        return back();
    }
}
