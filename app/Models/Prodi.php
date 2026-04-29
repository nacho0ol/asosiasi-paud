<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'user_id', 'nama_prodi', 'nama_universitas', 'kota', 'provinsi',
        'email', 'telepon', 'nama_kaprodi', 'status_pendaftaran'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }

    public function memberProdi()
    {
        return $this->hasOne(MemberProdi::class);
    }
}
