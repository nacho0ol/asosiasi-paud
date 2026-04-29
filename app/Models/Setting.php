<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_asosiasi', 'singkatan', 'tagline', 'alamat', 'email', 'telepon', 'website',
        'logo', 'ttd_ketua', 'nama_ketua', 'ttd_bendahara', 'nama_bendahara',
        'iuran_dosen', 'iuran_prodi', 'cap', 'mode_ttd',
        'kode_ttd_ketua', 'kode_ttd_bendahara', 'ttd_ketua_at', 'ttd_bendahara_at',
        'youtube', 'instagram', 'facebook'
    ];

    protected $casts = [
        'ttd_ketua_at'     => 'datetime',
        'ttd_bendahara_at' => 'datetime',
    ];
}
