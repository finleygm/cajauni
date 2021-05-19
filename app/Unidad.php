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
        'rubro_id',        
    ];  
    public function rubro(){
        return $this->belongsTo(Rubro::class);
    } 
}
