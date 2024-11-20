<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Equipo_incendio extends Authenticatable
{
    use HasFactory;

    protected $table = 'equipo_contra_incendio';

    protected $fillable = [
        'sistema_automatico',
        'tipo_sistema',
        'observaciones_sa',
        'red_contra_incendios',
        'hidrantes',
        'tipo_hidrante',
        'distancia',
        'observaciones_hyr',
        'extintores',
        'capacitacion',
        'observaciones'
    ];


    public function concept()
    {
                            ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Concept', 'id_equipo', 'id');
    }

    public function extinror_sistema_incendio()
    {
                            ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Extintor_sistema_incendio', 'id_equipo', 'id');
    }
}