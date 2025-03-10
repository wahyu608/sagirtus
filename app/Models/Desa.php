<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Data;

class Desa extends Model
{
    protected $table = 'desa';

    protected $fillable = [
        'nama',
        'longitude',
        'latitude',
    ];

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
