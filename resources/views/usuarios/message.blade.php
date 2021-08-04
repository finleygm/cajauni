

@extends('layouts.master')
@section('contenido')  
    <div class="row">    
      <div class="col-md-6">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">{{$titulo}}</h3>
              </div>              
              @csrf 
              {{ method_field('PUT') }}
              <div class="card-body">
                  <div class="alert alert-success">
                    <p>{{$contenido}}</p>                    
                  </div>              
            </div>        
        </div>
    </div>
@endsection