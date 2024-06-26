<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request){
        if($request)
        {
           
           
        }  
        $lcliente=Cliente::orderBy('id','desc')->paginate(15);    
        
        return view('cliente.index',["lcliente"=>$lcliente]);            
    }
    public function create(){
        return view('cliente.create');
    }
    public function store(Request $request){
        $cliente=new Cliente();      
        $nombres=$request->get('nombres');
        $apellidos=$request->get('apellidos');
        $ci=$request->get('ci');        
        if($request->get('ci_expedido')!=null){
            $ci_expedido=$request->get('ci_expedido');  
        }else{
            $ci_expedido="";
        }
        $cliente->nombres=strtoupper($nombres);
        $cliente->apellidos=strtoupper($apellidos);
        $cliente->ci=$ci;
        $cliente->ci_expedido=$ci_expedido;
        dd($cliente);
        $cliente_aux=Cliente::where('ci','=',$ci)->first();
        if($cliente_aux==null){
            $cliente->save();        
            return redirect()->route('cliente.index');
        }else{          
            return Redirect::back()->withInput()->withErrors(['Numero de Cuenta ya existente']);          
        }
    }
    public function edit($id){
        $cliente=Cliente::findOrFail($id);
        return view('cliente.edit',[
          "cliente"=>$cliente          
        ]);
    } 
    public function update(Request $request, $id){
        $cliente=Cliente::findOrFail($id);      
        $nombres=$request->get('nombres');
        $apellidos=$request->get('apellidos');
        $ci=$request->get('ci');        
        $ci_expedido=$request->get('ci_expedido');

        if($cliente!=null){
            $cliente->nombres=strtoupper($nombres);
            $cliente->apellidos=strtoupper($apellidos);
            $cliente->ci=$ci;
            $cliente->ci_expedido=$ci_expedido;
            $cliente->update();        
            return redirect()->route('cliente.index');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal cliente']);          
        }
    }
    public function show($id){
        $cliente=Cliente::findOrFail($id);        
        return view("cliente.show",[      
          "cliente"=>$cliente      
      ]);
    }
    public function ajaxCliente($ci){
        $cliente=Cliente::where("ci",'=',$ci)->first();
        return $cliente;     
   }
   public function ajaxClienteStore(Request $request){
        $cliente=new Cliente();      
        $nombres=$request->get('nombres');
        $apellidos=$request->get('apellidos');        
        $ci=$request->get('ci');    
        if($request->get('ci_expedido')!=null){
            $ci_expedido=$request->get('ci_expedido');  
        }else{
            $ci_expedido="";
        }
        
        $cliente->nombres=strtoupper($nombres);
        $cliente->apellidos=strtoupper($apellidos);
        $cliente->ci=$ci;
        $cliente->ci_expedido=$ci_expedido;
        $cliente_aux=Cliente::where('ci','=',$ci)->first();                
        if($cliente_aux==null){
            //dd($cliente);
            $cliente->save();  //SALIO UN ERROR ERA POR QUE LE STRING ERA 15 Y LA PRUEBA ERA DE 19 CARACTERES
            return ["error"=>0, "cliente"=>$cliente];      
        }else{          
            return ["error"=>1, "cliente"=>null];            
        }
   }
}
