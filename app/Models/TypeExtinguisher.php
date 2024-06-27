<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeExtinguisher extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['descripcion'];


    public function tipo_extintores_concepto()
    {
        return $this->hasMany('App\Models\TipoExtintorConcepto', 'id', 'id');
    }

}
