<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class botiquin_primeros_auxilios extends Authenticatable
{
    use HasFactory;

    protected $table = 'tipo_botiquin_Auxilios';
    
    protected $fillable = ['cantidad', 'tipo_botiquin_id', 'id_primeros_auxilios'];


    public function tipo_botiquin()
    {
        return $this->hasOne('App\Models\TypeKit', 'id', 'tipo_botiquin_id');
    }

    public function primeros_auxilios()
    {
        return $this->hasOne('App\Models\Primeros_auxilios', 'id', 'id_primeros_auxilios');
    }
    
}
