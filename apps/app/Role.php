<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Notifiable;
    protected $table = 'role';
    protected $fillable = [
        'role',
    ];
    public function role_user()
    {
        return $this->hasMany(User::class);
    }
}
