<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Concept extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'documento', 'telefono', 'disponibilidad', 'email', 'password', 'rol_id'];

    public function inspection()
    {
        return $this->hasOne('App\Models\Inspection', 'id', 'inspeccion_id');
    }

    public function type_estinguishers()
    {
        return $this->hasMany('App\Models\TypeExtinguisher', 'tipo_extintor_id', 'id');
    }

    public function type_kits()
    {
        return $this->hasMany('App\Models\TypeKit', 'tipo_botiquin_id', 'id');
    }
}
