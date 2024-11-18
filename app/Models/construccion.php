<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class construccion extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['anio_construccion' , 'nrs' , 'sst' , 'id_info_establecimiento' ];

    public function infoEstablecimiento(){
        return $this->hasOne('App\Models\construccion', 'id', 'id_info_establecimiento');
    }
    
    public function concept(){
        return $this->hasOne('App\Models\Concept', 'id_construccion', 'id');
    }


}