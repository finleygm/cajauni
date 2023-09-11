<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    //
    protected $table = 'pago_detalle';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[        
        'posicion',
        'monto',
        'descripcion',
        'pago_id',
        'cuenta_id',
        'cantidad',   
        'precio_unitario'     
    ];    
    public function cuenta(){
        return $this->belongsTo(Cuenta::class);
    }
    public function pago(){
        return $this->belongsTo(Pago::class);
    }
   

}
