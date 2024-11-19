<?php
////esta es de la clase info del establecimiento
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Establecimiento extends Authenticatable
{
    use HasFactory;

    protected $table = 'info_establecimiento';

    protected $filable = ['num_pisos' , 'ancho_dimensiones' , 'largo_dimensiones' , 'carga_ocupacional_fija' , 'carga_ocupacional_flotante' , 'id_empresa'];

    public function company(){
        return $this->hasOne('App\Models\Company', 'id' , 'id_empresa');
    }

    public function concept()
    {
                return $this->hasMany('App\Models\Concept' , 'id_info_establecimiento','id');
    }
}