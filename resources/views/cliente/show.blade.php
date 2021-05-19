@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">                
                </div>
                <h3 class="profile-username text-center">INFORMACIÓN DE CLIENTE</h3>
                <p class="text-muted text-center">DESCRIPCIÓN</p>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Carnet de Identidad</b> <a class="float-right">{{$cliente->ci}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>NOMBRES</b> <a class="float-right">{{$cliente->nombres}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>APELLIDOS</b> <a class="float-right">{{$cliente->apellidos}}</a>
                  </li>
                </ul>
                <a href="#" class="btn btn-primary btn-block"><b>Aceptar</b></a>
              </div>
            </div>              
        </div>
    </div>
@endsection