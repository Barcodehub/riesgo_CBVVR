<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class sistema_electrico extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['caja_distribucion_breker' , 
                        'encuentra_identificados' , 
                        'sistema_cableado_protegido',
                        'toma_corriente_corto',
                        'toma_corriente_sobrecarga',
                        'identificacion_voltaje',
                        'cajetines_asegurados',
                        'boton_emergencia',
                        'mantenimiento_preventivo',
                        'periodicidad',
                        'personal_idoneo',
                        'observaciones'];


    public function concept()
    {
        ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Concept', 'id_sistema_electrico', 'id');
    }
}
