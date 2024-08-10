<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Concept extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['fecha_concepto', 'carga_ocupacional_fija', 'carga_ocupacional_flotante', 'anios_contruccion', 'nrs10', 'sgsst', 'sist_automatico_incendios', 'observaciones_sist_incendios', 
                        'descripcion_concepto', 'hidrante', 'tipo_hidrante', 'capacitacion', 'tipo_camilla', 'inmovilizador_vertical', 'capacitacion_primeros_auxilios', 'inspeccion_id', 'favorable'];

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
}
