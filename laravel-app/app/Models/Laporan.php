<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $guarded = ['id'];
    protected $casts = ['dibuat_pada' => 'datetime'];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
