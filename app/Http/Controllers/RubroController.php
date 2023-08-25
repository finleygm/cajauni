<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rubro;
use Illuminate\Support\Facades\Redirect;
class RubroController extends Controller
{
    //
   
    public function index(Request $request){
        if($request)
        {            
           
        }  
        $lrubro1=Rubro::orderBy('id','desc')->paginate(15);        
        return view('rubro.index',["lrubro"=>$lrubro1]);            
    }
    public function create(){
        return view('rubro.create');
    }
    public function store(Request $request){
        $rubro=new Rubro();      
        $numero_identificador=$request->get('numero_identificador');
        $descripcion=$request->get('descripcion');
        
        $rubro->numero_identificador=$numero_identificador;
        $rubro->descripcion=$descripcion;    
        $rubro_aux=Rubro::where('numero_identificador','=',$numero_identificador)->first();
        if($rubro_aux==null){
            $rubro->save();        
            return redirect()->route('rubro.index');
        }else{          
            return Redirect::back()->withInput()->withErrors(['Rubro ya existe']);          
        }
    }
    public function edit($id){
        $rubro=Rubro::findOrFail($id);
        return view('rubro.edit',[
          "rubro"=>$rubro          
        ]);
    } 
    public function update(Request $request, $id){
        $rubro=Rubro::findOrFail($id);
        $numero_identificador=$request->get('numero_identificador');
        $descripcion=$request->get('descripcion');                         
        if($rubro!=null){
            $rubro->numero_clasificador=$numero_identificador;
            $rubro->descripcion=$descripcion;
            $rubro->update();        
            return redirect()->route('rubro.index');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal rubro']);          
        }
    }
    public function show($id){
        $rubro=Rubro::findOrFail($id);        
        return view("rubro.show",[      
          "rubro"=>$rubro      
      ]);
    }
    public function ajaxGetRubro(){
        return Rubro::orderBy('descripcion','asc')->get();
    }
    
}
