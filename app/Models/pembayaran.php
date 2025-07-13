<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pemesanan_id',
        'no_pembayaran',
        'metode_pembayaran',
        'jumlah_dibayar',
        'status',
        'tanggal_bayar',
        'bukti_bayar',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function pemesanan()
    {
        return $this->belongsTo(pemesanan::class);
    }
}
