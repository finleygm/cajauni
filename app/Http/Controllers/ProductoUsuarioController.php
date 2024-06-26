<?php

namespace App\Http\Controllers;

use App\Cuenta;
use App\CuentaClasificador;
use App\CuentaProdClasificador;
use App\ProductoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;

class ProductoUsuarioController extends Controller
{
     public function index(){

        $cuentaPC=ProductoUsuario::all();        
        return view('productoUsuario.index',["cuentaPCI"=>$cuentaPC]);            
    }

    public function create(Request $request ){
        $id=$request->get('id');
        $email=urldecode($request->get('email'));
        $name=urldecode($request->get('name'));
        return view('productoUsuario.create',['id'=>$id, 'email'=>$email, 'name'=>$name]);
    }

    public function edit($idr){
         return view('cuenta_prod_clasificador.edit',['id'=>$idr]);
       
    }

    public function delete($id)
    {
       $numeroEntero = intval($id);
      $prodUs =Cuenta::where('numero_cuenta',$numeroEntero)->orderBy('id','desc')->first();
   //   dd($prodUs->id);
   $user_id = Auth::id();
    // dd($user_id);
      $pagocat=ProductoUsuario::where('cuenta_id',$prodUs->id )->where('user_id', $user_id)->first();

         //   $prodUs = ProductoUsuario::find($numeroEntero);
         //   $buscarIdClasificador = CuentaProdClasificador::where('cuenta_clasificador_id',$cuenta_clasificadorD->cuenta_clasificador_id)->get();
          //  dd($buscarIdClasificador);
        
            if($pagocat->id!=""){ // dd($pagocat->id);
            $pagocat->delete();
            $success = true;
           // DB::rollback();      
           $respuesta=(object)[];
           $respuesta->error=0;
          return json_encode($respuesta);
          }else{
            $respuesta=(object)[];
            $respuesta->error=1;
            $respuesta->mensaje='Error al borrar';
            return json_encode($respuesta);
          }
         //   
     //   $cuenta_clasificadorD = ProductoUsuarioController::find($id);
//$buscarIdClasificador = ProductoUsuarioController::where('cuenta_clasificador_id',$cuenta_clasificadorD->cuenta_clasificador_id)->get();
      //  dd($buscarIdClasificador);
    //   $cantidad=$buscarIdClasificador->count();
    
    //    if($cantidad>1){
     
   //     $cuenta_clasificadorD->delete();

   //   }else{
   //     $cuenta_clasificadorD->delete();
    //    $id_=$buscarIdClasificador[0]->cuenta_clasificador_id;
   ///     $buscarClasId= CuentaClasificador::find($id_);
   //     $buscarClasId->delete();
    //    
   //     $respuesta=(object)[];
   //     $respuesta->error=0;
   //     $respuesta->mensaje='Borrado';
   //     return json_encode($respuesta);
  //    }
     //   

    }



    public function store(Request $request){
       
        //id_clasificador //0 -1

        $cuenta_clasificador=new ProductoUsuario(); 
        dd($cuenta_clasificador);
        $numero_clasificador=$request->get('cuentaTP');
        $descripcion=$request->get('cuentaClas');
        $unidad_id=$request->get('descripcion');                
        $cuenta_clasificador->cuenta_id=$numero_clasificador;
        $cuenta_clasificador->cuenta_clasificador_id=$descripcion;
        $cuenta_clasificador->descripcion=$unidad_id;             
    }

    public function ajaxproductoUsuario(Request $request){       
        $productUser=(object)$request->prod_users;
        $cuenta=(object)$request->cuenta;                
        //dd($productUser);
       // dd($cuenta);
        $tproductoUser=new ProductoUsuario();
        $pr=ProductoUsuario::where('cuenta_id',$cuenta->id )->where('user_id',$productUser->id)->first();
        
//dd($pr);
        if($pr!=""){
         $cuenta_asociada=(object)[]; 
         $cuenta_asociada->error=2;
          return json_encode($cuenta_asociada) ;
        }else{
          $tproductoUser->cuenta_id=$cuenta->id;
          $tproductoUser->user_id=$productUser->id;
          $tproductoUser->save();
       //   dd($cuentaRe);
          $cuenta_asociada=(object)[];
          $cuenta_asociada->error=0;
          $cuenta_asociada->cuenta_id=$cuenta->id;
          $cuenta_asociada->numero_cuenta=$cuenta->numero_cuenta;
          $cuenta_asociada->nombre_cuenta=$cuenta->nombre_cuenta;
          $cuenta_asociada->precio_unitario=$cuenta->precio_unitario;
          $cuenta_asociada->tipo_cuenta=$cuenta->tipo_cuenta;
          $cuenta_asociada->id=$tproductoUser->id;
          // dd($cuenta_asociada);
          return json_encode($cuenta_asociada) ;
        }


    }
 
    public function cargaProdUser($user_id){
        $datos_clasificados=ProductoUsuario::join('cuenta','cuenta.id','productousuario.cuenta_id')                                    
                                        ->where('productousuario.user_id','=',$user_id)
                                        ->select(
                                            'cuenta.id',
                                            'cuenta.numero_cuenta',
                                            'cuenta.nombre_cuenta',
                                            'cuenta.tipo_cuenta',
                                            'cuenta.precio_unitario',                                       
                                        )
                                        ->get(); 

              // dd($datos_clasificados);
        
       // $datos_clasificados_p=Cuenta::where("id",'=',$datos_clasificados->cuenta_id)->get();
        
    
      //  $datos_agrupados=(object)[];
        //$datos_agrupados->cuenta_clasificador=$cuenta_clasificador;
//$datos_agrupados->listado_cuentas=$datos_clasificados;
        // $datos_agrupados->numero_cuenta=$datos_clasificados_p->numero_cuenta;
        // $datos_agrupados->nombre_cuenta=$datos_clasificados_p->nombre_cuenta;
        // $datos_agrupados->tipo_cuenta=$datos_clasificados_p->tipo_cuenta;
        // $datos_agrupados->stock=$datos_clasificados_p->stock;
        // $datos_agrupados->descripcion=$cuenta_clasificador->descripcion;
       // dd($datos_agrupados);
 /*   if(($datos_agrupados->cuenta_clasificador->id)!=null){
            return json_encode($datos_agrupados) ;
    }else{
             return json_encode($datos_agrupados->cuenta_clasificador);
    }
          */

          return $datos_clasificados;
    }


}