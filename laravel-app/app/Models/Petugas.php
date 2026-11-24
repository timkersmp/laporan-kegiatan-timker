<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
    protected $guarded = ['id'];
    protected $hidden = ['password'];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'penugasan');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}
