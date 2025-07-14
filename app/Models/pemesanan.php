<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemesanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'lokasi_id',
        'no_pemesanan',
        'jumlah',
        'status',
        'catatan',
        'total_harga',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lokasi()
    {
        return $this->belongsTo(lokasi::class);
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id', 'id');
    }

}
