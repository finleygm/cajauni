<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubro';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[                
        'numero_identificador',
        'descripcion',
        
    ];  
    
}
