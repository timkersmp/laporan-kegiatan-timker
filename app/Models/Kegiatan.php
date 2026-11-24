<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $guarded = ['id'];
    protected $casts = ['tanggal_kegiatan' => 'date'];

    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'penugasan');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}
