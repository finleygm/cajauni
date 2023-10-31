

@extends('layouts.master')
@section('contenido')
    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de Rubros
            <a href="{{route('rubro.create')}}"><button class="btn btn-success">Nuevo Rubro</button></a> 
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero Clasificador</th>
                    <th>Descripcion</th>
                                    
                    <th>Opciones</th>
                    </thead>
                    @foreach($lrubro as $rubro)
                    <tr>
                        <td>{{$rubro->numero_identificador}}</td>
                        <td>{{$rubro->descripcion}}</td>
                        
                        <td>
                        <a href="{{route('rubro.show',$rubro->id)}}"><button class="btn btn-primary">Detalle</button></a>                      
                        <a href="{{route('rubro.edit',$rubro->id)}}" ><button class="btn btn-primary">Editar</button></a>                        
                        </td>
                    </tr>
                  
                    @endforeach
                </table>
            </div>
            {{$lrubro->render()}}
        </div>
    </div>
@endsection