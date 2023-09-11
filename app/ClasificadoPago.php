<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClasificadoPago extends Model
{
    protected $table = 'clasificador_pago';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[        
        'id',
        'concepto',
    ];


    public function pago(){
        return $this->hasMany(Pago::class,'clasificador_pago_id');
    }
}
