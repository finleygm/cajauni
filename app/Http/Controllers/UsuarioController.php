<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
class UsuarioController extends Controller
{
     
  protected $user;

  public function __construct(User $user)
  {
      $this->user = $user;
  }

  public function index() {               
   // $Usuario=User::all();
   // $Usuario['user']=User::paginate(10);
   // $Usuario = User::paginate(10);
   $Usuario = DB::table('users')->simplePaginate(10);
    return view('auth.listaruser',compact('Usuario'));   
   } 

    public function edit($id){ 
     $usuario=User::find($id);
        return view('auth.edit',compact('usuario'));
       
    } 
    public function update(Request $request, $id){
       // dd($request);
        $usuario=User::findOrFail($id);      
       // $numero_cuenta=$request->get('numero_cuenta');
        $nombre_usuario=$request->get('name');
        $correo=$request->get('email');    
        $sexo=$request->get('sexo');   
        $cargo=$request->get('cargo');     
        $area=$request->get('area');                         
        if($usuario!=null){
       //     $usuario->numero_cuenta=$numero_cuenta;
            $usuario->name=$nombre_usuario;
            $usuario->email=$correo;
            $usuario->sexo=$sexo;
            $usuario->cargo=$cargo;
            $usuario->categoria=$area;
            $usuario->update();        
            return redirect('usuario')->with('mensaje','Actualizado Correctamente');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal cuenta']);          
        }
    }

    public function show($id){
        $userdeta=User::findOrFail($id);        
        return view('auth.detalleuser',compact('userdeta'));
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
    
    public function mostrarUltimo(){
     
      $user = User::latest()->first()->id;
      $userdeta=User::findOrFail($user);

      return view('auth.detalleuser',compact('userdeta'));
      
     
  }
}
