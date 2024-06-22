<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Inspection extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['fecha_solicitud', 'establecimiento_id', 'inspector_id', 'estado', 'valor'];

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'establecimiento_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'inspector_id');
    }
}
