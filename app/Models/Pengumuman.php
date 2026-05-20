<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes;

    protected $table = 'pengumuman';
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'file',
        'user_id'
    ];
}
