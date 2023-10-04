@extends('layouts.master')
@section('contenido')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">

      <div class="card-body box-profile">

        <h3 class="profile-username text-center">CONSOLIDADO </h3>
        <p class="text-muted text-center">DESCRIPCIÓN</p>

        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">RUBRO</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE RUBRO</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">CUENTA CLASIFICADOR DESCRIPCION</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">ID DE PAGO</th> 
  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">PAGO SERIE</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">PAGO CATEGORIA</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">CLIENTE CI</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">CLIENTE NOMBRES</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">CLIENTE APELLIDOS/th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">SUBTOTAL</th>  
              </tr>
            </thead>
            <tbody>


              @php
              $suma_monto=0;
              @endphp

              
              @foreach ( $lconsolidado as $consolidado)
              <tr class="odd">
                <td class="dtr-control" tabindex="0">{{$consolidado->numero_identificador}}</td>
                <td>{{$consolidado->rubro_descripcion}}</td>
                <td>{{$consolidado->descripcionClasificador}}</td>
                <td>{{$consolidado->id_pago_detalle}}</td>
                <td>{{$consolidado->categoria}}</td>
                <td>{{$consolidado->serie}}</td>
                <td>{{$consolidado->ci}}</td>
                <td>{{$consolidado->nombres}}</td>
                <td>{{$consolidado->apellidos}}</td>
                <td>{{$consolidado->subtotal}}</td>
                @php
                $suma_monto+=$consolidado->subtotal;
                @endphp
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th rowspan="1" colspan="9">TOTAL BS.</th>
                <th rowspan="1" colspan="1">{{$suma_monto}}</th>
              </tr>
            </tfoot>
          </table>
        </div>

        <a href="{{route('reportes.ajaxGetExtracto',['fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin ])}}" class="btn btn-primary btn-block"><b>IMPRIMIR</b></a>


      </div>
    </div>
  </div>
</div>
@endsection