<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Sistema_iluminacion extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['sistema_iluminacion',
        'fecha_ultima_prueba',
        'observaciones'
    ];


    public function concept()
    {
        ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Concept', 'id_sistema_iluminacion', 'id');
    }
}
