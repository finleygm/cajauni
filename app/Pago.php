<?php

namespace App;
date_default_timezone_set('America/La_Paz');
use Illuminate\Database\Eloquent\Model;
class Pago extends Model
{
    //
    protected $table = 'pago';    
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[        
        'serie',
        'cliente_id',
        'fecha_pago',  
        'lugar', 
        'total',
        'user_id',
        'sector',
        'categoria',
        'nro_recibo',
        'clasificador_pago_id',
        'estado_pago',
        'justificacion',
        'fecha_anulacion'
    ];    
    public function detalle_pago()
    {        
       return $this->hasMany(PagoDetalle::class,'pago_id');//pago_id
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }


    public function getFechaPagoStr(){
        if($this->fecha_pago!=null)
        
            return \DateTime::createFromFormat('Y-m-d H:i:s',$this->fecha_pago)->format('d/m/Y H:i:s');
        else 
            return "";
    }
    public function getNumeroSerieStr(){
        $length = 6;
        $numero_serie = substr(str_repeat(0, $length).$this->serie, - $length);
        return $numero_serie;
    }
    public function getTotalALiteral(){
        $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
        $totali=(int)$this->total;
        $numero_decimal=(100*($this->total-$totali));
        $length = 2;
        $numero_decimal = substr(str_repeat(0, $length).$numero_decimal, - $length);
        return strtoupper($formatterES->format($totali))."    ".$numero_decimal."/100 Bs";
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function clasificador_pago(){
        return $this->belongsTo(ClasificadoPago::class);
    }
    // public function getTotalStr(){        
    // }
}
