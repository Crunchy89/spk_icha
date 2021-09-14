<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespondenKuisioner extends Model
{
    //
    protected $table = "responden_kuisioner";
    protected $fillable = [
        "nim", 'kode_mk', 'nidn', 'komentar'
    ];
}
