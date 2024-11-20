<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Concept extends Authenticatable
{
    use HasFactory;

    ////Agregar las demas foraneas

    protected $fillable = [
        'fecha_concepto',
        'inspeccion_id',
        'favorable',
        'recomendaciones',
        'id_info_establecimiento',
        'id_construccion',
        'id_imagen',///falta
        'id_sistema_electrico',
        'id_sistema_iluminacion',
        'id_ruta',
        'id_otros',
        'id_almacenamiento',
        'id_equipo',
        'id_auxilios'
    ];

    public function inspection()
    {
        return $this->hasOne('App\Models\Inspection', 'id', 'inspeccion_id');
    }

    public function tipo_extintor_conceptos()
    {
        return $this->hasMany('App\Models\TipoExtintorConcepto', 'concepto_id', 'id');
    }

    public function tipo_botiquin_conceptos()
    {
        return $this->hasMany('App\Models\TipoBotiquinConcepto', 'concepto_id', 'id');
    }

    public function infoestablecimiento() 
    {
        return $this->hasOne('App\Models\Establecimiento', 'id' , 'id_info_establecimiento');
    }

    public function construccion()
    {
        return $this->hasOne('App\Models\construccion', 'id', 'id_construccion');
    }

    public function  archivos()
    {
        return $this->hasMany('App\Models\Archivos', 'id', 'id_imagen');
    }

    public function sistema_electrico(){
        return $this->hasOne('App\Models\Sistema_electrico', 'id' ,'id_sistema_electrico');
    }

    public function sistema_iluminacion(){
        return $this->hasOne('App\Models\Sistema_iluminacion', 'id' ,'id_sistema_iluminacion');
    }
     
    public function ruta_evacuacion(){
        return $this->hasOne('App\Models\Ruta_evacuacion', 'id' ,'id_ruta');
    }

    public function otras_condiciones(){
        return $this->hasOne('App\Models\Otras_condiciones', 'id' ,'id_otros');
    }

    public function almacenamiento(){
        return $this->hasOne('App\Models\Almacenamiento', 'id_almacenamiento' ,'id');
    }
    
    public function equipo_incendio(){
        return $this->hasOne('App\Models\Equipo_incendio', 'id' ,'id_equipo');
    }

    public function primeros_auxilios(){
        return $this->hasOne('App\Models\Primeros_auxilios', 'id' ,'id_auxilios');
    }
     
}
