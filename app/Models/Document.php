<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Document extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['tipo_documento', 'archivo', 'empresa_id'];


    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'empresa_id');
    }
    
}
