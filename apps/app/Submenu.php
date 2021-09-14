<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Submenu extends Model
{
    //
    use Notifiable;
    protected $table = "submenu";
    protected $fillable =
    [
        "title",
        "link",
        "aktif",
        "urutan",
        "menu_id"
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'urutan', 'aktif', 'menu_id'
    ];

    public function submenu_id_menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
