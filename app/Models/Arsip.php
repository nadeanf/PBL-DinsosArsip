<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'user_id',
        'judul',
        'nomor',
        'tahun',
        'id_kategori',
        'jenis_arsip',
        'status_akses',
        'bagian',
        'lokasi',
        'deskripsi',
        'status_approval'
    ];

    // 🔥 Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // 🔥 Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 Relasi ke file
    public function files()
    {
        return $this->hasMany(File::class);
    }
}