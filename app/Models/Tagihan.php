<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'no_tagihan', 'jenis', 'ref_id', 'jumlah',
        'jatuh_tempo', 'status', 'pembayaran_id', 'keterangan'
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'ref_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'ref_id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
