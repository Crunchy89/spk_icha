<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespondenKriteria extends Model
{
    //
    protected $table = "responden_kriteria";
    protected $fillable = [
        'label', 'nama_responden'
    ];
}
