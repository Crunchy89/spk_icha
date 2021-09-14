<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    //
    protected $table = "mata_kuliah";
    protected $fillable = [
        'kode_mk', 'nama_mk'
    ];
}
