<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Otras_condiciones extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'condicion',
        'observacion'
    ];


    public function concept()
    {
                            ////modelo///////////////// clave primaria del modelo///////clave foranea en la tabla actual
        return $this->hasOne('App\Models\Concept', 'id_otros', 'id');
    }
}