<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategory extends Model
{

    protected $table = 'kategories';
    protected $fillable = [
        'jenis',
    ];

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
