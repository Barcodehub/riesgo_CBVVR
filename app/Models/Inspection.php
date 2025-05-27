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
        return $this->belongsTo('App\Models\Company', 'establecimiento_id', 'id');
    }
    

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'inspector_id');
    }

    public function concept()
    {
        return $this->hasMany('App\Models\Concept', 'inspeccion_id', 'id');
    }

    public function risks()
{
    return $this->belongsToMany(Risk::class, 'inspection_risk', 'inspection_id', 'risk_id')
                ->withPivot('observations', 'severity')
                ->withTimestamps();
}
}
