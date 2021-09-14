<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NilaiKuisioner extends Model
{
    //
    protected $table = "nilai_kuisioner";
    protected $fillable = [
        "id_responden_kuisioner", 'id_kriteria'
    ];
}
