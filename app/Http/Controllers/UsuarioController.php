<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
class UsuarioController extends Controller
{
     
    public function edit($id){
        // $usuario=User::findOrFail($id);
        // return view('usuario.edit',[
        //   "usuario"=>$usuario          
        // ]);
    } 
    public function update(Request $request, $id){
        // $usuario=Usuario::findOrFail($id);      
        // $numero_cuenta=$request->get('numero_cuenta');
        // $nombre_cuenta=$request->get('nombre_cuenta');
        // $descripcion=$request->get('descripcion');    
        // $precio_unitario=$request->get('precio_unitario');   
        // $clasificador_cuenta_id=$request->get('clasificador_cuenta_id');                    
        // if($usuario!=null){
        //     $usuario->numero_cuenta=$numero_cuenta;
        //     $usuario->nombre_cuenta=$nombre_cuenta;
        //     $usuario->descripcion=$descripcion;
        //     $usuario->precio_unitario=$precio_unitario;
        //     $usuario->cuenta_clasificador_id=$clasificador_cuenta_id;//esta invertido 
        //     $usuario->update();        
        //     return redirect()->route('usuario.index');
        // }else{          
        //   return Redirect::back()->withInput()->withErrors(['No existe tal cuenta']);          
        // }
    }
    public function show($id){
      //   $usuario=User::findOrFail($id);        
      //   return view("usuario.show",[      
      //     "usuario"=>$usuario      
      // ]);
    }
    public function edit_contrasenha($id){
      $usuario=User::findOrFail($id);
      return view('usuarios.cambiar_contrasenha',['usuario'=>$usuario]); 
    }
    public function update_contrasenha(Request $request, $id){
        $usuario=User::findOrFail($id);        
        $password=$request->get('old_password');
        $new_password=$request->get('new_password');
        $confirm_password=$request->get('confirm_password');        
        if(Hash::check($password, $usuario->password)){
              if($new_password!=$confirm_password){
              //  dd("Error");
                 //return redirect()->back()->with('error','Contraseña se cambio con exito');
                 return Redirect::back()->withErrors(['No coinciden las contraseña']);
              }else{
                 $usuario->password=Hash::make($new_password);
                 $usuario->update();
              }
           //return redirect()->route('home')->with('success','Contraseña se cambio con exito');
           return view('usuarios.message',['titulo'=>'Información','contenido'=>'Se cambio exitosamente la contraseña']); 
        }else{
          return view('usuarios.cambiar_contrasenha',['usuario'=>$usuario]); 
        }        
    }
}
