@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">                
                </div>
                <h3 class="profile-username text-center">INFORMACIÓN DE UNIDADES</h3>
                <p class="text-muted text-center">DESCRIPCIÓN</p>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>NUMERO UNIDAD</b> <a class="float-right">{{$unidad->numero_unidad}}</a>
                  </li>                  
                  <li class="list-group-item">
                    <b>DESCRIPCION</b> <a class="float-right">{{$unidad->descripcion}}</a>
                  </li>
                 
                </ul>
                <a href="#" class="btn btn-primary btn-block"><b>Aceptar</b></a>
              </div>
            </div>              
        </div>
    </div>
@endsection