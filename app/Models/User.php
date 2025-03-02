<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'documento', 'telefono', 'telefono2' , 'disponibilidad', 'email', 'email2' , 'password', 'rol_id', 'acceso_huella'];

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'rol_id');
    }

    public function inspections()
    {
        return $this->hasMany('App\Models\Inspection', 'inspector_id', 'id');
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'cliente_id', 'id');
    }


    public function huella()
{
    return $this->hasOne('App\Models\Huella', 'id_user');
}
}
