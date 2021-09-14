<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Menu extends Model
{
    //
    use Notifiable;
    protected $table = "menu";
    protected $fillable =
    [
        "title",
        "icon",
        "link",
        "aktif",
        "urutan",
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'urutan', 'aktif'
    ];

    public function menu_submenu()
    {
        return $this->hasMany(Submenu::class);
    }

    public function menu_akses()
    {
        return $this->hasMany(Akses::class);
    }
}
