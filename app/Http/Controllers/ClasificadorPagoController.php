<?php

namespace App\Http\Controllers;

use App\ClasificadoPago;
use Illuminate\Http\Request;

class ClasificadorPagoController extends Controller
{
    public function ajaxclasiPago(){
        $prueba =ClasificadoPago::all(); 
        return $prueba;
    }

    public function register(Request $request ){
        $pago_clasificado=$request->concepto;
        $pago_class=new ClasificadoPago();
        $pago_class->concepto=$pago_clasificado;
        $pago_class->id=null;
        $pago_class->save();
        $aux= (Object)[];
        $aux->listado_clasificador=$this->ajaxclasiPago();
        $aux->pago_class=$pago_class;
        return json_encode($aux);

    }
}
