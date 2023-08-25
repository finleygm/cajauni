<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Cuenta;
use App\CuentaClasificador;


class CuentaController extends Controller
{
    public function index(Request $request){
        if($request)
        {
            // $numero_cuenta=trim($request->get('numero_cuenta'));//filtro de busqueda
            // $lcuentas1=Cuenta::where('numero_cuenta',$numero_cuenta);                        
            // $filtro_esp=false;
            // if($alumno!=null){
            //     $alumno_id=$alumno->id;
            //     $whereRaw=" alumno_id=$alumno_id ";
            //     $filtro_esp=true;                
            // }                                       
            // if($filtro_esp){
            //         $alumnos_estado=AlumnoConclusion::whereRaw($whereRaw)
            //         ->orderBy('id', 'desc')                    
            //         ->paginate(15);
            //         return view('cuenta.index',["lcuentas"=>$lcuentas1]);       
            // }   
           
        }  
        $lcuentas1=Cuenta::orderBy('id','desc')->paginate(15);        
        return view('cuenta.index',["lcuentas"=>$lcuentas1]);            
    }
    public function create(){
        return view('cuenta.create');
    }
    public function store(Request $request){
        $cuenta=new Cuenta();      
        $numero_cuenta=$request->get('numero_cuenta');
        $nombre_cuenta=$request->get('nombre_cuenta');
        $descripcion=$request->get('descripcion');        
        $precio_unitario=$request->get('precio_unitario');        
        $rubro_id=$request->get('rubro_id');  
        $unidad_id=$request->get('unidad_id');    
        $tipo_cuenta=$request->get('tipo_cuenta');    
        $stock=$request->get('stock');        
        $cuenta->numero_cuenta=$numero_cuenta;
        $cuenta->nombre_cuenta=$nombre_cuenta;
        $cuenta->descripcion=$descripcion;
        $cuenta->precio_unitario=$precio_unitario;
        $cuenta->rubro_id=$rubro_id;
        $cuenta->unidad_id=$unidad_id;
        $cuenta->tipo_cuenta=$tipo_cuenta;
        $cuenta->stock=$stock;

      

        $cuenta_aux=Cuenta::where('numero_cuenta','=',$numero_cuenta)->first();
        if($cuenta_aux==null){
            $cuenta->save();        
            return redirect()->route('cuenta.index');
        }else{          
            return Redirect::back()->withInput()->withErrors(['Numero de Cuenta ya existente']);          
        }
    }
    public function edit($id){
        $cuenta=Cuenta::findOrFail($id);
        return view('cuenta.edit',[
          "cuenta"=>$cuenta          
        ]);
    } 
    public function update(Request $request, $id){
        $cuenta=Cuenta::findOrFail($id);      
        $numero_cuenta=$request->get('numero_cuenta');
        $nombre_cuenta=$request->get('nombre_cuenta');
        $descripcion=$request->get('descripcion');    
        $precio_unitario=$request->get('precio_unitario');   
        $clasificador_cuenta_id=$request->get('clasificador_cuenta_id');                    
        if($cuenta!=null){
            $cuenta->numero_cuenta=$numero_cuenta;
            $cuenta->nombre_cuenta=$nombre_cuenta;
            $cuenta->descripcion=$descripcion;
            $cuenta->precio_unitario=$precio_unitario;
            $cuenta->cuenta_clasificador_id=$clasificador_cuenta_id;//esta invertido 
            $cuenta->update();        
            return redirect()->route('cuenta.index');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal cuenta']);          
        }
    }
    public function show($id){
        $cuenta=Cuenta::findOrFail($id);        
        return view("cuenta.show",[      
          "cuenta"=>$cuenta      
      ]);
    }
    public function ajaxCuenta(){
         return Cuenta::all();      
    }
    public function ajaxCuentaPorClasificador($id){
        return Cuenta::where('cuenta_clasificador_id','=',$id)->get();
    }
    public function ajaxClasificadorPorUnidad($id){
        return CuentaClasificador::where('unidad_id','=',$id)->get();
    }
    public function ajaxCuente($numero_cuenta){
        $cuenta=Cuenta::where("numero_cuenta",'=',$numero_cuenta)->first();
    
        return $cuenta;     
   }
}
