@extends('layouts.master')
@section('contenido')

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Usuraios<a href="venta/create">
                <a href="{{route('authreg')}}"><button class="btn btn-primary">Nuevo</button></a>
    </div>
</div>
<div class="row" id="app">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Numero</th>
                    <th>Nombre y Apellidos</th>
                    <th>Email</th>
                    <th>Opciones</th>
                </thead>
                @php
                $aux=0;
                @endphp
                @foreach ($Usuario as $use)
                <tr>
                    <td>{{$aux=$aux+1}}</td>
                    <td>{{$use->name}}</td>
                    <td>{{$use->email}}</td>
                    <td>
                        <a href="{{route('usuario.detallado',$use->id)}}"><button class="btn btn-primary">Detalle</button></a>
                        <a href="{{route('usuario.edit',$use->id)}}"><button class="btn btn-primary">Editar</button></a>
                        <a href="{{route('prod_cuenta.create',['id'=>$use->id,'email'=>$use->email,'name'=>$use->name ])}}"><button class="btn btn-primary">Permisos-Venta</button></a>

                </tr>
                @endforeach
            </table>

                   <div class="container-fluid">
                      {!! $Usuario->links()!!}
                   </div> 
                 
           
        </div>
    </div>
</div>
@endsection