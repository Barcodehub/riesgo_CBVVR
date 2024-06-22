<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Document extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['tipo_documento', 'archivo'];


    public function document()
    {
        return $this->hasOne('App\Models\Document', 'id', 'document_id');
    }
    
}
