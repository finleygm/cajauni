<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaClasificador extends Model
{
    protected $table = 'cuenta_clasificador';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[        
        'id',
        'numero_clasificador',
        'descripcion',
       'unidad_id'
    ];
    public function unidad(){
        return $this->belongsTo(Unidad::class);
    } 
     
}
