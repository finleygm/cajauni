

@extends('layouts.master')
@section('contenido')
    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de Unidades</h3>
            <a href="{{route('cuenta_clasificador.create')}}"><button class="btn btn-primary">Nuevo</button></a>             
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero de Clasificador</th>
                    <th>Descripcion</th>
                    <th>Unidad</th>                   
                    <th>Rubro</th>                   
                    <th>Opciones</th>
                    </thead>
                    @foreach($lcuenta_clasificador as $cuenta_clasificador)
                    <tr>
                        <td>{{$cuenta_clasificador->numero_clasificador}}</td>
                        <td>{{$cuenta_clasificador->descripcion}}</td>
                        <td>{{$cuenta_clasificador->unidad->descripcion}}</td>
                        <td>{{$cuenta_clasificador->unidad->rubro->descripcion}}</td>                    
                        <td>
                        <a href="{{route('cuenta_clasificador.show',$cuenta_clasificador->id)}}"><button class="btn btn-primary">Detalle</button></a>                      
                        <a href="{{route('cuenta_clasificador.edit',$cuenta_clasificador->id)}}" ><button class="btn btn-primary">Editar</button></a>                      
                        </td>  
                    </tr>                  
                    @endforeach
                </table>
            </div>
            {{$lcuenta_clasificador->render()}}
        </div>
    </div>
@endsection