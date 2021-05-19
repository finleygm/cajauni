

@extends('layouts.master')
@section('contenido')
    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de Unidades<a href="venta/create">
            <a href="{{route('unidad.create')}}"><button class="btn btn-primary">Nueva Unidad</button></a>             
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero Unidad</th>
                    <th>Descripcion</th>                                    
                    <th>Opciones</th>
                    </thead>
                    @foreach($lunidades as $unidad)
                    <tr>
                        <td>{{$unidad->numero_unidad}}</td>
                        <td>{{$unidad->descripcion}}</td>
                        
                        <td>
                        <a href="{{route('unidad.show',$unidad->id)}}"><button class="btn btn-primary">Detalle</button></a>                      
                        <a href="{{route('unidad.edit',$unidad->id)}}" ><button class="btn btn-primary">Editar</button></a>                        
                        </td>
                    </tr>                  
                    @endforeach
                </table>
            </div>
            {{$lunidades->render()}}
        </div>
    </div>
@endsection