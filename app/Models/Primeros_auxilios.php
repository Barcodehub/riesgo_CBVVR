<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Primeros_auxilios extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'camilla',
        'inmovilizador_cervical',
        'inmovilizador_extremidades',
        'capacitacion_primeros_auxilios',
        'tipo_camilla',
        'tipo_inm_cervical',
        'tipo_inm_extremidades',
        'tipo_capacitacion',
        'observaciones',
    ];


    public function botiquin()
    {
        ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasMany('App\Models\botiquin_primeros_auxilios', 'id_primeros_auxilios', 'id');
    }
}
