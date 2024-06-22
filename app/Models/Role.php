<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'rol_id', 'id');
    }
}
