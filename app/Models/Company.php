<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'razon_social',
        'nombre_establecimiento',
        'representante_legal',
        'horario_funcionamiento',
        'cedula_representante',
        'nit',
        'direccion',
        'barrio',
        'telefono',
        'email',
        'actividad_comercial',
        'cliente_id'
    ];


    public function inspections()
    {
        ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasMany('App\Models\Inspection', 'establecimiento_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'empresa_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'cliente_id');
    }

    public function info_establecimiento()
    {
        return $this->hasMany('App\Models\Establecimiento', 'id_empresa', 'id');
    }
}
