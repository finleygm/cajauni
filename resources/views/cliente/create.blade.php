

@extends('layouts.master')
@section('contenido')  
    <div class="row">
    @if (count($errors)>0)
                <div class="alert alert-danger" >
                    <ul>
                    @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                    @endforeach
                    </ul>
                </div>
        @endif
    <div class="col-md-6">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Pago</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('cliente.store')}}"method="POST"  mid="form-cuenta">
              @csrf 
           
                <div class="card-body">
                  <div class="form-group">
                    <label for="ci">Carnet de identidad</label>
                    <input type="text" class="form-control" id="ci" placeholder="Carnet de Identidad" name="ci" value="{{old('ci')}}">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Carnet de identidad expedido</label>
                    <input type="text" class="form-control" id="ci_expedido" placeholder="EXpedición CI" name="ci_expedido" value="{{old('ci_expedido')}}">
                  </div>        

                  <div class="form-group">
                    <label for="nombres">NOMBRES</label>
                    <input type="text" class="form-control" id="nombres" placeholder="Nombres" name="nombres" value="{{old('nombres')}}" >
                  </div>        
                  <div class="form-group">
                    <label for="apellidos">APELLIDOS</label>
                    <input type="text" class="form-control" id="apellidos" placeholder="Apellidos" name="apellidos" value="{{old('apellidos')}}" >
                  </div>                     
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
              </form>
            </div>
        
        </div>
    </div>
@endsection