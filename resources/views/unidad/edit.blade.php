

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
                <h3 class="card-title">Editar Unidad</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('unidad.update',$unidad->id)}}" method="POST"  id="form-unidad">
              @csrf 
              {{ method_field('PUT') }}
                <div class="card-body">
                  <div class="form-group">
                    <label for="inumero_unidad">Numero de Unidad</label>
                    <input type="text" class="form-control" id="inumero_unidad" placeholder="Numero de Cuenta" name="numero_unidad" value="{{old('numero_unidad',$unidad->numero_unidad)}}">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Descripcion de Unidad</label>
                    <input type="text" class="form-control" id="inombre_cuenta" placeholder="Descripcion" name="descripcion" value="{{old('descripcion',$unidad->descripcion)}}">
                  </div>        
                  <div class="form-group">
                    <label for="tipo_unidad">Tipo de unidad</label>
                      <select class="form-control" id="tipo_unidad" name="tipo_unidad" placeholder="tipo de unidad">  
                      <option value="Administracion" selected>Administracion</option>
                      <option value="Carrera">Carrera</option>
                      <option value="Modulo"> Modulo</option>
                      <option value="Modulo"> Submodulo</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="unidad_id">Unidad a la pertenece</label>
                      <select class="form-control" id="unidad_id" name="unidad_id" placeholder="unidad_id">
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
            cargarRubros(); 
            cargarUnidad();           
            function cargarRubros()
            {           
              //para chosen el combox que te permite select amigable
              //npm install chosen-js    
                  $.ajax({
                        url: '/ajax/ajaxRubros',
                        type: 'post',
                        data : { name : $("#name").value },
                        dataType: 'json',
                        success:function(response){                              
                              var len = response.length;
                              console.log(response);
                              $("#irubro_id").empty();
                              for( var i = 0; i<len; i++){
                                    var rubro = response[i];
                                    var id = rubro.id;
                                    var descripcion = rubro.descripcion;
                                   var htmlinsert="<option value='"+id+"'>"+descripcion+"</option>";                                 
                                   $("#irubro_id").append(htmlinsert);
                              }
                             // jQuery(function($){ $("#irubro_id").chosen(); });
                            $("#irubro_id").chosen({no_results_text: "No se encontro la organización"});     
                        }
                  });
            }   
            
            function cargarUnidad() {
      //para chosen el combox que te permite select amigable
      //npm install chosen-js    
      $.ajax({
        url: '/ajax/ajaxUnidad',
        type: 'get',
        dataType: 'json',
        success: function(response) {
          var len = response.length;
          console.log(response);
          $("#unidad_id").empty();
          for (var i = 0; i < len; i++) {
            var unidad_id = response[i];
            var id = unidad_id.id;
            var descripcion = unidad_id.descripcion;
            var htmlinsert = "<option value='" + id + "'>" + descripcion + "</option>";
            $("#unidad_id").append(htmlinsert);
          }
          $("#unidad_id").chosen({
            no_results_text: "No se encontro la organización"
          });
        }
      });
    }
            

      // $(document).ready(function(){           
      //       $.ajaxSetup({
      //             headers:{
      //                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
      //             }
      //       });         
      //       cargarRubros({{old('rubro_id',$unidad->rubro_id)}});            
      //       function cargarRubros(rubro_id)
      //       {           
      //             $.ajax({
      //                   url: '/ajax/ajaxRubros',
      //                   type: 'get',
      //                   dataType: 'json',
      //                   success:function(response){                              
      //                         var len = response.length;
      //                         console.log(response);
      //                         $("#irubro_id").empty();
      //                         for( var i = 0; i<len; i++){
      //                               var rubro = response[i];
      //                               var id = rubro.id;
      //                               var descripcion = rubro.descripcion;
      //                              var select=(rubro_id!=null&rubro_id==id)?" selected":"";                                     
      //                              var htmlinsert="<option value='"+id+"'"+select+">"+descripcion+"</option>";                                 
      //                              $("#irubro_id").append(htmlinsert);
      //                         }
      //                       $("#irubro_id").chosen({no_results_text: "No se encontro la organización"});     
      //                   }
      //             });
      //       }    
      });
</script>
@endpush