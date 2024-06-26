@extends('layouts.master')
@section('contenido')


    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de cuentas
            <a href="{{route('cuenta.create')}}"><button class="btn btn-success">Nuevo Cuenta</button></a> 
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero de Cuenta del Producto</th>
                    <th>Nombre del Producto</th>
                    <th>Rubro</th>
                    <th style="text-align: center;">Unidad del Producto</th>  
                    <th style="text-align: center;">Precio del Producto</th> 
                    <th style="text-align: center;">Tipo de Clasificacion del Producto</th>    
                    <th style="text-align: center;">Cantidad/Stock</th>    
                    <th>Opciones</th>
                    </thead>
                    @foreach($lcuentas as $cuenta)
                    <tr>
                        <td>{{$cuenta->numero_cuenta}}</td>
                        <td>{{$cuenta->nombre_cuenta}}</td>
                        <td>{{$cuenta->rubro->numero_identificador}}</td>
                        <td style="text-align: center;">{{$cuenta->unidad}}</td>           
                        <td  style="text-align: center;" >{{$cuenta->precio_unitario}}</td>
                        @if (($cuenta->tipo_cuenta)==1)                    
                        <td style="text-align: center;">Es producto</td>
                        @else                              
                        <td style="text-align: center;">No es producto</td>                             
                        @endif          
                        <td  style="text-align: center;" >{{$cuenta->stock}}</td>
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