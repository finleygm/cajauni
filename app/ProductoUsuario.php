<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoUsuario extends Model
{
    protected $table = 'productousuario';    
    public $timestamps=false;
    protected $fillable=[        
        'cuenta_id',
        'user_id',
    ];
 
     
}
