<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAkses extends Model
{
    protected $table = 'request_akses';

    protected $fillable = [
        'user_id',
        'arsip_id',
        'status'
    ];

    // 🔗 relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 relasi ke arsip
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}
