<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Extintor_sistema_incendios extends Authenticatable
{
    use HasFactory;

    protected $table = 'tipo_extintor_equipo';

    protected $fillable = ['empresa_recarga', 'fecha_recarga', 'fecha_vencimiento',  'cantidad',  'tipo_extintor_id', 'id_equipo_contra_incendio'];


    public function tipo_extintor()
    {
        return $this->hasOne('App\Models\TypeExtinguisher', 'id', 'tipo_extintor_id');
    }

    public function equipo_incendio()
    {
        return $this->hasOne('App\Models\Equipo_incendio', 'id', 'id_equipo_contra_incendio');
    }
}
