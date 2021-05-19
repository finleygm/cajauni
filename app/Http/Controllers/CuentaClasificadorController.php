<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuenta;
use App\CuentaClasificador;
use App\Unidad;
use Illuminate\Support\Facades\Redirect;

class CuentaClasificadorController extends Controller
{
    public function index(Request $request){
        if($request)
        {            
           
        }  
        $lcuenta_clasificador1=CuentaClasificador::orderBy('id','desc')->paginate(15);        
        return view('cuenta_clasificador.index',["lcuenta_clasificador"=>$lcuenta_clasificador1]);            
    }
    public function create(){
        return view('cuenta_clasificador.create');
    }
    public function store(Request $request){
        $cuenta_clasificador=new CuentaClasificador();      
        $numero_clasificador=$request->get('numero_clasificador');
        $descripcion=$request->get('descripcion');
        $unidad_id=$request->get('unidad_id');                
        $cuenta_clasificador->numero_clasificador=$numero_clasificador;
        $cuenta_clasificador->descripcion=$descripcion;
        $cuenta_clasificador->unidad_id=$unidad_id;
       

        $cuenta_clasificador_aux=CuentaClasificador::where('numero_clasificador','=',$cuenta_clasificador)->first();
        if($cuenta_clasificador_aux==null){
            $cuenta_clasificador->save();        
            return redirect()->route('cuenta_clasificador.index');
        }else{          
            return Redirect::back()->withInput()->withErrors(['Numero de Cuenta ya existente']);          
        }
    }
    public function edit($id){
        $cuenta_clasificador=CuentaClasificador::findOrFail($id);
        return view('cuenta_clasificador.edit',[
          "cuenta_clasificador"=>$cuenta_clasificador          
        ]);
    } 
    public function update(Request $request, $id){
        $cuenta_clasificador=CuentaClasificador::findOrFail($id);  
        $numero_clasificador=$request->get('numero_clasificador');
        $descripcion=$request->get('descripcion');
        $unidad_id=$request->get('unidad_id');                    
        if($cuenta_clasificador!=null){
            $cuenta_clasificador->numero_clasificador=$numero_clasificador;
            $cuenta_clasificador->descripcion=$descripcion;
            $cuenta_clasificador->unidad_id=$unidad_id;
            $cuenta_clasificador->update();        
            return redirect()->route('cuenta_clasificador.index');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal Clasificador de cuenta']);          
        }
    }
    public function show($id){
        $cuenta_clasificador=CuentaClasificador::findOrFail($id);        
        return view("cuenta_clasificador.show",[      
          "cuenta_clasificador"=>$cuenta_clasificador      
      ]);
    }
    public function ajaxGetCuentaClasificador(){
         return CuentaClasificador::all();      
    }
    public function ajaxGetUnidad(){
        return Unidad::all();      
   }
}
