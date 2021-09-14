<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NilaiPilihan extends Model
{
    //
    protected $table = "nilai_pilihan";
    protected $fillable = [
        'nama_pilihan', 'nilai'
    ];
}
