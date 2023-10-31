

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
                <h3 class="card-title">Editar Cuenta</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('cuenta.update',$cuenta->id)}}" method="POST"  mid="form-cuenta">
              @csrf 
              {{ method_field('PUT') }}
                <div class="card-body">
                  <!-- <div class="form-group">
                     <label for="iclasificador_cuenta_id"> Clasificador de Cuenta</label>
                      <Select class="form-control" id="iclasificador_cuenta_id" name="clasificador_cuenta_id">
                      <option>vacio</option>
                      </select>
                   </div> -->
                  <div class="form-group">
                    <label for="inumero_cuenta">Numero de Cuenta</label>
                    <input type="text" class="form-control" id="inumero_cuenta" placeholder="Numero de Cuenta" name="numero_cuenta" value="{{old('numero_cuenta',$cuenta->numero_cuenta)}}">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Nombre de Producto</label>
                    <input type="text" class="form-control" id="inombre_cuenta" placeholder="Nombre de cuenta" name="nombre_cuenta" value="{{old('nombre_cuenta',$cuenta->nombre_cuenta)}}">
                  </div>        
                  <div class="form-group">
                    <label for="unidade">Unidad</label>
                    <input type="text" class="form-control" id="unidad" placeholder="Describa que tipo de unidad de producto ingresara" name="unidad" value="{{old('unidad',$cuenta->unidad)}}">
                  </div>
                  <div class="form-group">
                    <label for="iprecio_unitario">Precio Unitario</label>
                    <input type="text" class="form-control" id="iprecio_unitario" placeholder="Precio Unitario" name="precio_unitario" value="{{old('precio_unitario',$cuenta->precio_unitario)}}">
                  </div>  
                  <div class="form-group">
                    <label for="idescripcion">Descripción</label>
                    <input type="text" class="form-control" id="idescripcion" placeholder="Descripcion" name="descripcion" value="{{old('descripcion',$cuenta->descripcion)}}" >
                  </div>  
                          <!-- /.card-body -->
                    <div class="form-group">
                      <label for="rubro_id">Nombre de Rubro/Partidas Ingreso</label>
                      <Select class="form-control" id="rubro_id" name="rubro_id">
                        <option>vacio</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="unidad_id"> Unidad recaudadora</label>
                      <Select class="form-control" id="unidad_id" name="unidad_id">
                        <option>vacio</option>
                      </select>
                    </div>
                    @if($cuenta->tipo_cuenta ==1)
                    <div class="form-group">
                      <label for="tipo_cu">Tipo de cuenta</label>
                      <div class="form-check">
                        <input class="form-check-input" value="1" type="radio" name="tipo_cuenta" id="tipo_cuenta_produc" checked>
                        <label class="form-check-label" for="tipo_cuenta_produc">
                          Cuenta de producto
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" value="0" type="radio" name="tipo_cuenta" id="tipo_cuenta_noproduc"  >
                        <label class="form-check-label" for="tipo_cuenta_noproduc">
                        No es cuenta de producto
                        </label>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="idescripcion">Stock</label>
                      <input type="text" class="form-control" id="stock" placeholder="Stock" name="stock" value="{{old('stock',$cuenta->stock)}}">
                    </div>
                    
                    @endif      
                    </div>
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
$(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    cargarRubro();
    cargarUnidad();

    function cargarRubro() {
      //para chosen el combox que te permite select amigable
      //npm install chosen-js    
      $.ajax({
        url: '/ajax/ajaxRubros',
        type: 'get',
        dataType: 'json',
        success: function(response) {
          var len = response.length;
          console.log(response);
          $("#rubro_id").empty();
          for (var i = 0; i < len; i++) {
            var rubro_id = response[i];
            var id = rubro_id.id;
            var descripcion = rubro_id.descripcion;
            var htmlinsert = "<option value='" + id + "'>" + descripcion + "</option>";
            $("#rubro_id").append(htmlinsert);
          }
          $("#rubro_id").chosen({
            no_results_text: "No se encontro la organización"
          });
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

    function prueba(){

        console.log($cuenta);




    }
  


      // $(document).ready(function(){           
      //       $.ajaxSetup({
      //             headers:{
      //                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
      //             }
      //       }); 
      //       cargarClasificadorCuenta({{old('clasificador_cuenta_id',$cuenta->cuenta_clasificador_id)}});            
      //       function cargarClasificadorCuenta(cuenta_clasificador_id)
      //       {           
      //         //para chosen el combox que te permite select amigable
      //         //npm install chosen-js    
      //             $.ajax({
      //                   url: '/ajax/ajaxCuentaClasificador',
      //                   type: 'get',
      //                   dataType: 'json',
      //                   success:function(response){                              
      //                         var len = response.length;
      //                         console.log(response);
      //                         $("#iclasificador_cuenta_id").empty();
      //                         for( var i = 0; i<len; i++){
      //                               var clasificador_cuenta = response[i];
      //                               var id = clasificador_cuenta.id;
      //                               var descripcion = clasificador_cuenta.descripcion;                             
      //                               var select=(cuenta_clasificador_id!=null&cuenta_clasificador_id==id)?" selected":"";                                     
      //                              var htmlinsert="<option value='"+id+"' "+select+">"+descripcion+"</option>";                                 
      //                              $("#iclasificador_cuenta_id").append(htmlinsert);
      //                         }
      //                       $("#iclasificador_cuenta_id").chosen({no_results_text: "No se encontro la organización"});     
      //                   }
      //             });
      //       }    
      });
      this.prueba();
</script>
@endpush