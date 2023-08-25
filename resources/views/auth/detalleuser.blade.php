@extends('layouts.master')
@section('contenido')

@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session :: get('mensaje')}}
    <i class="fa fa-check-circle verde"></i>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif
<br>
<div class="row">
    <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr class="table-secondary">
                        <th scope="col">Datos</th>
                        <th scope="col">Detalles</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    <tr>
                        <th scope="row">Nombre y Apellido: </th>
                        <td>{{$userdeta->name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Correo: </th>
                        <td>{{$userdeta->email}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Sexo: </th>
                        <td>{{$userdeta->sexo}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Categoria: </th>
                        <td>{{$userdeta->cargo}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Fecha de Registro: </th>
                        <td>{{$userdeta->created_at}}</td>
                    </tr>
                </tbody>
            </table>
    </div>
</div>
@endsection