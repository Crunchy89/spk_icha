<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    //
    protected $table = "jadwal";
    protected $fillable = [
        'nim', 'kode_mk', 'nidn'
    ];
}
