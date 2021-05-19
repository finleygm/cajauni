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
                <h3 class="card-title">Registro de Clasificador de cuenta</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('cuenta_clasificador.store')}}"method="POST"  mid="form-cuenta">
              @csrf 
                <div class="card-body">
                  <div class="form-group">
                    <label for="inumero_clasificador">Numero Clasificador</label>
                    <input type="text" class="form-control" id="inumero_clasificador" placeholder="Numero Clasificador" name="numero_clasificador" value="{{old('numero_clasificador',)}}">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Descripcion Clasificador</label>
                    <input type="text" class="form-control" id="idescripcion" placeholder="Descripcion" name="descripcion" value="{{old('descripcion')}}">
                  </div>        
                  <div class="form-group">
                    <label for="iunidad_id">Unidades</label>
                    <select class="form-control" id="iunidad_id" name="unidad_id" placeholder="Unidades">
                    <option value="5">values</option>
                    <option value="6">values1</option>
                    </select>
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
@push ('scripts')
<script>      
      $(document).ready(function(){           
            $.ajaxSetup({
                  headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                  }
            }); 
            // $('form').bind("keypress", function(e) {
            //       if (e.keyCode == 13) {               
            //             e.preventDefault();
            //             return false;
            //       }
            // });
            cargarUnidad();    
           // alert("msg");        
            function cargarUnidad()
            {           
              //para chosen el combox que te permite select amigable
              //npm install chosen-js    
                  $.ajax({
                        url: '/ajax/ajaxUnidad',
                        type: 'get',
                        dataType: 'json',
                        success:function(response){                              
                              var len = response.length;
                              console.log(response);
                              //alert("Hola");
                              $("#iunidad_id").empty();
                              for( var i = 0; i<len; i++){
                                    var unidad = response[i];
                                    var id = unidad.id;
                                    var descripcion = unidad.descripcion;
                                   var htmlinsert="<option value='"+id+"'>"+descripcion+"</option>";                                 
                                   $("#iunidad_id").append(htmlinsert);
                              }
                             // jQuery(function($){ $("#irubro_id").chosen(); });
                            $("#iunidad_id").chosen({no_results_text: "No se encontro la organización"});     
                        }
                  });
            }    
      });
</script>
@endpush