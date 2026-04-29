<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MemberDosen extends Model
{
    protected $fillable = [
        'dosen_id', 'no_member', 'tanggal_mulai', 'tanggal_berakhir',
        'status', 'notif_terkirim', 'kode_verifikasi', 'hash_dokumen'
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
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'notif_terkirim' => 'boolean',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'ref_id')->where('jenis', 'dosen');
    }
}
