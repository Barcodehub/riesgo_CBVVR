<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TipoExtintorConcepto extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['empresa_recarga', 'fecha_vencimiento', 'id_tipo_extintor', 'id_equipo'];


    public function tipo_extintor()
    {
        return $this->hasOne('App\Models\TypeExtinguisher', 'id', 'id_tipo_extintor');
    }

    public function equipo_incendio()
    {
        return $this->hasOne('App\Models\Equipo_incendio', 'id', 'id_equipo');
    }
    
}
