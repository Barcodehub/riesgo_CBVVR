<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeExtintorConcept extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['empresa_recarga', 'fecha_vencimiento', 'tipo_extintor_id', 'concepto_id'];


    public function tipo_extintor()
    {
        return $this->hasOne('App\Models\TypeExtinguisher', 'id', 'tipo_extintor_id');
    }

    public function concepto()
    {
        return $this->hasOne('App\Models\Concept', 'id', 'concepto_id');
    }
    
}
