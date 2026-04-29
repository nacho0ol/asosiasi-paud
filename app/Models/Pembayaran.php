<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pembayaran extends Model
{
    protected $fillable = [
        'no_kwitansi', 'jenis', 'ref_id', 'jumlah',
        'tanggal_bayar', 'keterangan', 'metode', 'kode_verifikasi', 'hash_dokumen'
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->kode_verifikasi)) {
                $model->kode_verifikasi = strtoupper(Str::random(12));
            }
        });
    }

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function memberDosen()
    {
        return $this->belongsTo(MemberDosen::class, 'ref_id');
    }

    public function memberProdi()
    {
        return $this->belongsTo(MemberProdi::class, 'ref_id');
    }
}
