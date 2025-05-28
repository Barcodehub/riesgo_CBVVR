<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'risk_type',
        'severity',
        'latitude',
        'longitude',
        'mitigation_measures'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    
public function concepts()
{
    return $this->hasManyThrough(
        Concept::class,
        Inspection::class,
        'establecimiento_id', // FK en inspections table
        'inspeccion_id',     // FK en concepts table
        'company_id',        // Local key en risks table
        'id'                 // Local key en inspections table
    );
}

    
}