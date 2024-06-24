<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeKitConcept extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['empresa_recarga', 'fecha_vencimiento', 'tipo_botiquin_id', 'concepto_id'];


    public function tipo_botiquin()
    {
        return $this->hasOne('App\Models\TypeKit', 'id', 'tipo_botiquin_id');
    }

    public function concepto()
    {
        return $this->hasOne('App\Models\Concept', 'id', 'concepto_id');
    }
    
}
