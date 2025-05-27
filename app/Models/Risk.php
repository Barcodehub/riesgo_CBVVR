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
}