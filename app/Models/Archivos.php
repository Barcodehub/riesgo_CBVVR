<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Archivos extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['tipo_archivo' , 'url'];

    public function concept()
    {
        return $this->hasOne('App\Models\Concept' , 'id_imagen' , 'id');
    }
}