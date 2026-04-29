<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = [
        'user_id', 'prodi_id', 'nama', 'nidn', 'email', 'telepon',
        'jabatan_fungsional', 'pendidikan_terakhir', 'foto', 'status_pendaftaran'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function memberDosen()
    {
        return $this->hasOne(MemberDosen::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
