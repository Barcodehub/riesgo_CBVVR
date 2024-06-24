<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeExtintor extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['descripcion'];


    public function tipo_extintores_concepto()
    {
        return $this->hasMany('App\Models\TypeExtintorConcept', 'id', 'id');
    }

}
