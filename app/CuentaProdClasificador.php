<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaProdClasificador extends Model
{
    protected $table = 'cuenta_producto_clasificador';    
    public $timestamps=false;
    protected $fillable=[        
        'cuenta_id',
        'cuenta_clasificador_id',
        'descripcion'
    ];
 
    public function cuenta(){
        return $this->belongsTo(Cuenta::class);
    }
    public function cuenta_clasificador(){
        return $this->belongsTo(CuentaClasificador::class);
    }
   
}
