<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stok_lpg extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'jenis_pemilik',
        'jumlah',
        'lokasi_id',
        'catatan',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function lokasi()
    {
        return $this->belongsTo(lokasi::class);
    }
}
