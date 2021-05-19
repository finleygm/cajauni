

@extends('layouts.master')
@section('contenido')
    <div class="row">      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de Clientes<a href="venta/create">
            <a href="{{route('cliente.create')}}"><button class="btn btn-primary">Nuevo</button></a>             
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Carnet de Identidad</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Opciones</th>
                    </thead>
                    @foreach($lcliente as $cliente)
                       <tr>
                        <td>{{$cliente->ci}}</td>
                        <td>{{$cliente->apellidos}}</td>
                        <td>{{$cliente->nombres}}</td>                    
                        <td>
                        <a href="{{route('cliente.show',$cliente->id)}}"><button class="btn btn-primary">Detalle</button></a>                      
                        <a href="{{route('cliente.edit',$cliente->id)}}" ><button class="btn btn-primary">Editar</button></a>                        
                      
                        </tr>
                    @endforeach
                </table>
            </div>
            {{$lcliente->render()}}
        </div>
    </div>
@endsection