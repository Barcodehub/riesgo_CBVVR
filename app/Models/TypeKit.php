<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeKit extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['descripcion'];


    public function tipo_botiquines_concepto()
    {
        return $this->hasMany('App\Models\TypeKitConcept', 'id', 'id');
    }

}
