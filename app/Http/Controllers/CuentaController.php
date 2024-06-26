<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Cuenta;
use App\CuentaClasificador;
use App\ProductoUsuario;

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
        return view('cuenta.create',["nro_cuenta"=>Cuenta::max('id')+1]);
    }
    public function store(Request $request){
        
        $cuenta=new Cuenta();      
        $numero_cuenta=$request->get('numero_cuenta');
        $nombre_cuenta=$request->get('nombre_cuenta');
       
        if($request->get('descripcion')==null){
        $descripcion="";
        }else{
           $descripcion=$request->get('descripcion');
        }
        if($request->get('precio_unitario')==null){
        $precio_unitario=0;
        }else{
            $precio_unitario=$request->get('precio_unitario');
        }
        $rubro_id=$request->get('rubro_id');  
        $unidad_id=$request->get('unidad_id');    
        $tipo_cuenta=$request->get('tipo_cuenta');    
        if($request->get('stock')==null){
            $stock=0;
            }else{
                $stock=$request->get('stock'); 
            }     
        $unidad=$request->get('unidad');          
        $cuenta->numero_cuenta=$numero_cuenta;
        $cuenta->nombre_cuenta=$nombre_cuenta;
        $cuenta->descripcion=$descripcion;
        $cuenta->precio_unitario=$precio_unitario;
        $cuenta->rubro_id=$rubro_id;
        $cuenta->unidad_id=$unidad_id;
        $cuenta->tipo_cuenta=$tipo_cuenta;
        $cuenta->unidad=$unidad;
      
        if($cuenta->stock!=null){
           $cuenta->stock=$stock;
        }else{
           $cuenta->stock=0;  
        }
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
     //   dd($request);    
        $numero_cuenta=$request->get('numero_cuenta');//
        $nombre_cuenta=$request->get('nombre_cuenta');//
        $descripcion=$request->get('descripcion');       // 
        $precio_unitario=$request->get('precio_unitario');  //      
        $rubro_id=$request->get('rubro_id');  //
        $unidad_id=$request->get('unidad_id');    //
        $tipo_cuenta=$request->get('tipo_cuenta');  //  
        $stock=$request->get('stock');      //
        $unidad=$request->get('unidad'); //    
        if($cuenta!=null){
            if($cuenta->tipo_cuenta==2){
            //    dd('llegue');
            $cuenta->numero_cuenta=$numero_cuenta;
            $cuenta->nombre_cuenta=$nombre_cuenta;
            $cuenta->descripcion=$descripcion;
            $cuenta->precio_unitario=$precio_unitario;
            $cuenta->rubro_id=$rubro_id;
            $cuenta->unidad_id=$unidad_id;
            $cuenta->unidad=$unidad;//esta invertido 
            $cuenta->update();        
            return redirect()->route('cuenta.index'); 
          }else{
         //   dd('mierd');
            $cuenta->numero_cuenta=$numero_cuenta;
            $cuenta->nombre_cuenta=$nombre_cuenta;
            $cuenta->descripcion=$descripcion;
            $cuenta->precio_unitario=$precio_unitario;
            $cuenta->rubro_id=$rubro_id;
            $cuenta->unidad_id=$unidad_id;
            $cuenta->tipo_cuenta=$tipo_cuenta;
            $cuenta->stock=$stock;
            $cuenta->unidad=$unidad;//esta invertido 
            $cuenta->update();        
            return redirect()->route('cuenta.index'); 
          }
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
    public function cargarBydUser($user_id){
  //  $cuenta=ProductoUsuario::where("user_id",'=',$user_id)->get();

    $cuenta=ProductoUsuario::join('cuenta','cuenta.id','productousuario.cuenta_id')                                    
    ->where('productousuario.user_id','=',$user_id)
    ->select(
        'cuenta.id',
        'cuenta.numero_cuenta',
        'cuenta.nombre_cuenta',
        'cuenta.tipo_cuenta',
        'cuenta.precio_unitario',                                       
    )
    ->get(); 

    return $cuenta;     
}
}
