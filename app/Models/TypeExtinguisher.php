<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeExtinguisher extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nombre', 'contenido'];


    public function extintor_sistema_incendio()
    {
        return $this->hasMany('App\Models\Extintor_sistema_incendios', 'id_tipo_extintor', 'id');
    }

}
