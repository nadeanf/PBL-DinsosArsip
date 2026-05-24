<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'parent_id',
        'masa_aktif'
    ];

    // relasi parent-child
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }

    public function children()
{
    return $this->hasMany(Kategori::class, 'parent_id');
}

public function childrenRecursive()
{
    return $this->children()->with('childrenRecursive');
}

public function arsip()
    {
        return $this->hasMany(\App\Models\Arsip::class, 'id_kategori');
    }
}