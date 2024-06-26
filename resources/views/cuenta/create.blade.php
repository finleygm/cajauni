@extends('layouts.master')
@section('contenido')
<div class="row">
  @if (count($errors)>0)
  <div class="alert alert-danger">
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
        <h3 class="card-title">Registro de Cuenta</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{route('cuenta.store')}}" method="POST" mid="form-cuenta">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="inumero_cuenta">Numero de Cuenta del Producto</label>
            <input type="text" class="form-control" id="inumero_cuenta" required placeholder="Indique el numero de cuenta de su producto a registrar" name="numero_cuenta" value="{{old('numero_cuenta',$nro_cuenta)}}">
          </div>
          <div class="form-group">
            <label for="inombre_cuenta">Nombre del Producto</label>
            <input type="text" class="form-control" id="inombre_cuenta" required placeholder="Indique el nombre del producto" name="nombre_cuenta" value="{{old('nombre_cuenta')}}">
          </div>
          
          <div class="form-group">
            <label for="unidade">Unidad</label>
            <input type="text" class="form-control" id="unidad" required placeholder="Describa que tipo de unidad de producto ingresara" name="unidad" value="{{old('unidad')}}">
          </div>

          <div class="form-group">
            <label for="iprecio_unitario">Precio Unitario</label>
            <input type="text" class="form-control" id="iprecio_unitario" placeholder="Indique el precio del producto" name="precio_unitario" value="{{old('precio_unitario')}}">
          </div>
          <div class="form-group">
            <label for="idescripcion">Descripción</label>
            <input type="text" class="form-control" id="idescripcion"  placeholder="Descrpcion del producto {opcional}" name="descripcion" value="{{old('descripcion')}}">
          </div>

          <div class="form-group">
            <label for="rubro_id">Nombre de Rubro/Partidas Ingreso</label>
            <Select class="form-control" required id="rubro_id" name="rubro_id">
              <option>vacio</option>
            </select>
          </div>
          <div class="form-group">
            <label for="unidad_id">Unidad recaudadora</label>
            <Select class="form-control" required id="unidad_id" name="unidad_id">
              <option>vacio</option>
            </select>
          </div>

          <div class="form-group">
            <label for="rubro_id">Tipo de cuenta</label>
          <div class="form-check">
            <input class="form-check-input" value="1" type="radio" checked name="tipo_cuenta" id="tipo_cuenta_produc">
            <label class="form-check-label" for="tipo_cuenta_produc">
              Cuenta para producto
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" value="2" type="radio" name="tipo_cuenta" id="tipo_cuenta_noproduc" >
            <label class="form-check-label" for="tipo_cuenta_noproduc">
             No es cuenta de producto
            </label>
          </div>
          </div>

          <div class="form-group">
            <label for="idescripcion">Stock</label>
            <input type="text" class="form-control"  id="stock" placeholder="Stock" name="stock" value="{{old('stock')}}">
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




  });
</script>
@endpush