<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RiwayatAkses extends Model
{
    protected $table = 'riwayat_akses';

    protected $fillable = [
        'user_id',
        'arsip_id',
        'aksi'
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}