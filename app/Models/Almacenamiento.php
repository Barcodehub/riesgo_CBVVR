<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Almacenamiento extends Authenticatable
{
    use HasFactory;

    protected $table ='almacenamiento_combustibles';

    protected $fillable = [
        'material_solido_ordinario',
        'zona_almacenamiento_1',
        'observaciones_1',
        'cantidad_1',
        'material_liquido_inflamable',
        'zona_almacenamiento_2',
        'observaciones_2',
        'cantidad_2',
        'material_gaseoso_inflamable',
        'zona_almacenamiento_3',
        'observaciones_3',
        'cantidad_3',
        'otros_quimicos',
        'zona_almacenamiento_4',
        'observaciones_4',
        'cantidad_4'
    ];


    public function concept()
    {
                            ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Concept', 'id_almacenamiento', 'id');
    }
}