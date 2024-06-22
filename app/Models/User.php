<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'documento', 'telefono', 'disponibilidad', 'email', 'password', 'rol_id'];

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'rol_id');
    }

    public function inspections()
    {
        return $this->hasMany('App\Models\Inspection', 'inspector_id', 'id');
    }
}
