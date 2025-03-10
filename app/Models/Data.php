<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategory;        
use App\Models\Desa;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data extends Model
{   
    use HasFactory;
    protected $table = 'data';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'kategory_id',
        'desa_id',
    ];

    public function kategory()
    {
        return $this->belongsTo(Kategory::class , 'kategory_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class , 'desa_id');
    }
}
