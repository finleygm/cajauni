<?php

namespace App\Http\Controllers;

use App\CuentaClasificador;
use App\CuentaProdClasificador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;

class CuentaProdClasificadorController extends Controller
{
     public function index(Request $request){
        if($request)
        {            
           
        }
     

        $cuentaPC=CuentaClasificador::all();        
        return view('cuenta_prod_clasificador.index',["cuentaPCI"=>$cuentaPC]);            
    }
    public function create(){
        return view('cuenta_prod_clasificador.create');
    }

    public function edit($idr){
         return view('cuenta_prod_clasificador.edit',['id'=>$idr]);
       
    }

    public function delete($id)
    {
        //dd($id);
        $cuenta_clasificadorD = CuentaProdClasificador::find($id);
        $buscarIdClasificador = CuentaProdClasificador::where('cuenta_clasificador_id',$cuenta_clasificadorD->cuenta_clasificador_id)->get();
      //  dd($buscarIdClasificador);
       $cantidad=$buscarIdClasificador->count();
    
        if($cantidad>1){
     
        $cuenta_clasificadorD->delete();

      }else{
        $cuenta_clasificadorD->delete();
        $id_=$buscarIdClasificador[0]->cuenta_clasificador_id;
        $buscarClasId= CuentaClasificador::find($id_);
        $buscarClasId->delete();
        
        $respuesta=(object)[];
        $respuesta->error=0;
        $respuesta->mensaje='Borrado';
        return json_encode($respuesta);
      }
     //   

    }



    public function store(Request $request){
       
        //id_clasificador //0 -1

        $cuenta_clasificador=new CuentaProdClasificador(); 
        dd($cuenta_clasificador);
        $numero_clasificador=$request->get('cuentaTP');
        $descripcion=$request->get('cuentaClas');
        $unidad_id=$request->get('descripcion');                
        $cuenta_clasificador->cuenta_id=$numero_clasificador;
        $cuenta_clasificador->cuenta_clasificador_id=$descripcion;
        $cuenta_clasificador->descripcion=$unidad_id;             
    }

    public function ajaxGuardarCuentaClasificada(Request $request){       
        $cuenta_clasificador1=(object)$request->cuenta_clasificador;
        $cuenta=(object)$request->cuenta;                
        // dd($cuenta_clasificador1);
        // dd($cuenta);
        
        
        $cuenta_clasificador_guardar=new CuentaClasificador();
        if($cuenta_clasificador1->id==-1){            
            $cuenta_clasificador_guardar->numero_clasificador=$cuenta_clasificador1->numero_clasificador;
            $cuenta_clasificador_guardar->descripcion=$cuenta_clasificador1->descripcion;
            $cuenta_clasificador_guardar->save();
            
        }else{
            $cuenta_clasificador_guardar=CuentaClasificador::find($cuenta_clasificador1->id);
            $cuenta_clasificador_guardar->descripcion=$cuenta_clasificador1->descripcion;
            $cuenta_clasificador_guardar->update();
        }
        
        $cuentaProdClasificador=new CuentaProdClasificador();
        $cuentaProdClasificador->cuenta_clasificador_id=$cuenta_clasificador_guardar->id;
        $cuentaProdClasificador->cuenta_id=$cuenta->id;
        $cuentaProdClasificador->save();
         dd($cuentaProdClasificador);

        $cuenta_asociada=(object)[];
        $cuenta_asociada->error=0;
        $cuenta_asociada->cuenta_clasificador_id=$cuenta_clasificador_guardar->id;
        $cuenta_asociada->numero_clasificador=$cuenta_clasificador1->numero_clasificador;
        $cuenta_asociada->descripcion=$cuenta_clasificador1->descripcion;
        $cuenta_asociada->numero_cuenta=$cuenta->numero_cuenta;
        // dd($cuenta_asociada);
        return json_encode($cuenta_asociada) ;
    }




}