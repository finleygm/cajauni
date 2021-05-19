

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
                <h3 class="card-title">Editar Clasficador de Cuenta</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('cuenta_clasificador.update',$cuenta_clasificador->id)}}" method="POST"  id="form-cuenta_clasificador">
              @csrf 
              {{ method_field('PUT') }}
                <div class="card-body">
                  <div class="form-group">
                    <label for="inumero_clasificador">Numero de Clasificador de Cuenta</label>
                    <input type="text" class="form-control" id="inumero_clasificador" placeholder="Numero de clasificador de cuenta" name="numero_clasificador" value="{{old('numero_unidad',$cuenta_clasificador->numero_clasificador)}}">
                  </div>
                  <div class="form-group">
                    <label for="idescripcion">Descripcion</label>
                    <input type="text" class="form-control" id="idescripcion" placeholder="Descripcion" name="descripcion" value="{{old('descripcion',$cuenta_clasificador->descripcion)}}">
                  </div>        
                  <div class="form-group">
                    <label for="iunidad_id">Unidad</label>
                    <select type="text" class="form-control" id="iunidad_id"name="unidad_id">
                    </select>                    
                  </div>                                                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
              </form>
            </div>
        
        </div>
    </div>
@endsection
@push ('scripts')
<script>      

      $(document).ready(function(){           
            $.ajaxSetup({
                  headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                  }
            });         
            cargarUnidad({{old('unidad_id',$cuenta_clasificador->unidad_id)}});            
            function cargarUnidad(unidad_id)
            {           
                  $.ajax({
                        url: '/ajax/ajaxUnidad',
                        type: 'get',
                        dataType: 'json',
                        success:function(response){                              
                              var len = response.length;
                              console.log(response);
                              $("#iunidad_id").empty();
                              for( var i = 0; i<len; i++){
                                    var rubro = response[i];
                                    var id = rubro.id;
                                    var descripcion = rubro.descripcion;
                                   var select=(unidad_id!=null&unidad_id==id)?" selected":"";                                     
                                   var htmlinsert="<option value='"+id+"'"+select+">"+descripcion+"</option>";                                 
                                   $("#iunidad_id").append(htmlinsert);
                              }
                            $("#iunidad_id").chosen({no_results_text: "No se encontro la organización"});     
                        }
                  });
            }    
      });
</script>
@endpush