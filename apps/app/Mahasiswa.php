<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    //
    protected $table = 'mhs';
    protected $fillable = [
        'nim', 'nama_mhs', 'jurusan'
    ];
}
