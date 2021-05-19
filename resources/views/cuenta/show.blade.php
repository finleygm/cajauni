@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">                
                </div>
                <h3 class="profile-username text-center">INFORMACIÓN DE CUENTA</h3>
                <p class="text-muted text-center">DESCRIPCIÓN</p>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>NUMERO DE CUENTA</b> <a class="float-right">{{$cuenta->numero_cuenta}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>NOMBRE DE CUENTA</b> <a class="float-right">{{$cuenta->nombre_cuenta}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>PRECIO UNITARIO</b> <a class="float-right">{{$cuenta->precio_unitario}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>DESCRIPCION </b> <a class="float-right">{{$cuenta->descripcion}}</a>
                  </li>
                </ul>
                <a href="#" class="btn btn-primary btn-block"><b>Aceptar</b></a>
              </div>
            </div>              
        </div>
    </div>
@endsection