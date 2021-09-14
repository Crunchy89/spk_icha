<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Akses extends Model
{
    //
    use Notifiable;
    protected $table = "akses";
    protected $fillable = ["menu_id", "role_id"];

    public function aksesRole_id()
    {
        return $this->belongsToMany(Role::class);
    }

    public function aksesMenu_id()
    {
        return $this->belongsToMany(Menu::class);
    }
}
