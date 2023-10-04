<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    //
    protected $table = 'cuenta';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[        
        'numero_cuenta',
        'nombre_cuenta',
        'descripcion',
        'precio_unitario',
        'rubro_id',
        'unidad_id',
        'tipo_cuenta',
        'stock',
    ];    
    public function cuenta_clasificador(){
        return $this->belongsTo(CuentaClasificador::class);
    } 

    public function rubro(){
        return $this->belongsTo(Rubro::class);
    }  
    
    public function unidad_ent(){
        return $this->belongsTo(Unidad::class,'unidad_id');
    } 
}
