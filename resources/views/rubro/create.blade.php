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
                <h3 class="card-title">Registro de Rubros</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('rubro.store')}}"method="POST"  mid="form-cuenta">
              @csrf 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inumero_clasificador">Numero Identificador</label>
                    <input type="text" class="form-control" id="inumero_identificador" placeholder="Numero Identificador" name="numero_identificador" value="{{old('numero_identificador')}}">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Descripcion Rubro</label>
                    <input type="text" class="form-control" id="idescripcion" placeholder="Descripcion" name="descripcion" value="{{old('descripcion')}}">
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
