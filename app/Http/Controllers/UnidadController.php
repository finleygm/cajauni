<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidad;
use App\Rubro;
use Illuminate\Support\Facades\Redirect;
class UnidadController extends Controller
{
    public function index(Request $request){
       // $unidadPru=Unidad::findOrFail($unidad_id);
        if($request)
        {                       
        }  
        $lunidad1=Unidad::orderBy('id','desc')->paginate(15);        
        return view('unidad.index',["lunidades"=>$lunidad1,]);            
    }
    public function create(){
        return view('unidad.create',['id_nuevo'=>Unidad::max('id')+1]);

    }
    public function store(Request $request){
        $unidad=new Unidad();   
       // dd($request);
        $numero_unidad=$request->get('numero_unidad');
        $descripcion=$request->get('descripcion');  
        $tipo_unidad=$request->get('tipo_unidad');    
        $unidad_id=$request->get('unidad_id');   
        $unidad->numero_unidad=$numero_unidad;
        $unidad->descripcion=$descripcion;    
        $unidad->tipo_unidad=$tipo_unidad;  
        $unidad->unidad_id=$unidad_id;
        $unidad_aux=Unidad::where('numero_unidad','=',$numero_unidad)->first();
        if($unidad_aux==null){
            $unidad->save();        
            return redirect()->route('unidad.index');
        }else{          
            return Redirect::back()->withInput()->withErrors(['Unidad ya existe']);          
        }
    }
    public function edit($id){
        $unidad=Unidad::findOrFail($id);
        return view('unidad.edit',[
          "unidad"=>$unidad          
        ]);
    } 
    public function update(Request $request, $id){
        $unidad=Unidad::findOrFail($id);
       // dd($request);
        $numero_unidad=$request->get('numero_unidad');
        $descripcion=$request->get('descripcion');                                 
        $tipo_unidad=$request->get('tipo_unidad');                                   
        $unidad_id=$request->get('unidad_id');   
      //  $unidadPru=Unidad::findOrFail($unidad_id);
                                                     
        if($unidad!=null){
            $unidad->numero_unidad=$numero_unidad;
            $unidad->descripcion=$descripcion;
            $unidad->tipo_unidad=$tipo_unidad;
            $unidad->unidad_id=$unidad_id;
            $unidad->update();        
            return redirect()->route('unidad.index');
        }else{          
          return Redirect::back()->withInput()->withErrors(['No existe tal unidad']);          
        }
    }
    public function show($id){
        $unidad=Unidad::findOrFail($id);        
        return view("unidad.show",[      
          "unidad"=>$unidad      
      ]);
    }
    public function ajaxGetUnidad(){
        return Unidad::orderBy('descripcion','asc')->get();
    }
}
