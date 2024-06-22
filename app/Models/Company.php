<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nombre', 'representante_legal', 'nit', 'direccion', 'telefono', 'email', 'actividad_comercial', 'ancho_dimensiones', 'largo_dimensiones', 'num_pisos'];


    public function inspections()
    {
        return $this->hasMany('App\Models\Inspection', 'establecimiento_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'empresa_id', 'id');
    }
}
