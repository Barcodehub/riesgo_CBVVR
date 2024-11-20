<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class botiquin_primeros_auxilios extends Authenticatable
{
    use HasFactory;

    protected $table = 'tipo_botiquin_Auxilios';
    
    protected $fillable = ['cantidad', 'id_botiquin', 'id_primeros_auxilios'];


    public function tipo_botiquin()
    {
        return $this->hasOne('App\Models\TypeKit', 'id', 'id_botiquin');
    }

    public function primeros_auxilios()
    {
        return $this->hasOne('App\Models\Primeros_auxilios', 'id', 'id_primeros_auxilios');
    }
    
}
