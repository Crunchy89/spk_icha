<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NilaiKriteria extends Model
{
    //
    protected $table = "nilai_kriteria";
    protected $fillable = [
        'id_responden_kriteria', 'id_kriteria', 'nilai'
    ];
}
