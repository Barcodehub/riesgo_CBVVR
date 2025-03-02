<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Huella extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'huellas';
    protected $hidden = ['pivot'];

    protected $fillable = [
        'id_user', 'huella'
    ];

    // belongsTo de user
    public function user(){
        return $this->belongsTo('App\Models\User');
    } 
}
