<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'parent_id'
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
        return $this->children()->with([
            'childrenRecursive' => function ($query) {
                $query->select('id', 'nama', 'parent_id');
            }
        ]);
    }
}