<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'arsip_id',
        'nama_file',
        'path_file',
        'tipe_file',
        'size'
    ];

    // 🔥 Relasi ke Arsip
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}