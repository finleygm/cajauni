@extends('layouts.master')
@section('contenido')
    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de cuentas<a href="venta/create">
            <a href="{{route('cuenta.create')}}"><button class="btn btn-primary">Nuevo</button></a> 
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero de Cuenta</th>
                    <th>Nombre de Cuenta</th>
                    <th>Clasificador de</th>                   
                    <th>Opciones</th>
                    </thead>
                    @foreach($lcuentas as $cuenta)
                    <tr>
                        <td>{{$cuenta->numero_cuenta}}</td>
                        <td>{{$cuenta->nombre_cuenta}}</td>
                        <td>{{$cuenta->cuenta_clasificador->descripcion}}</td>                    
                        <td>
                        <a href="{{route('cuenta.show',$cuenta->id)}}"><button class="btn btn-primary">Detalle</button></a>                      
                        <a href="{{route('cuenta.edit',$cuenta->id)}}" ><button class="btn btn-primary">Editar</button></a>                        
                    </tr>                  
                    @endforeach
                </table>
            </div>
            {{$lcuentas->render()}}
        </div>
    </div>
@endsection