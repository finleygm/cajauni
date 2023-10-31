<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidad';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[                
        'numero_unidad',
        'descripcion',   
        'tipo_unidad',
        'unidad_id',
    ];  

    public function unidad(){
        return $this->belongsTo(Unidad::class,'unidad_id','id');
    }
   
}
