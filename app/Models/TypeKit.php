<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TypeKit extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['descripcion'];


    public function primeros_auxilios()
    {
        return $this->hasMany('App\Models\botiquin_primeros_auxilios', 'id_botiquin', 'id');
    }

}
