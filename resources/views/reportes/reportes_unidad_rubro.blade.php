@extends('layouts.master')
@section('contenido')
<div class="row">
  <div class="col-md-8">
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
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">MONTO</th>
              </tr>
            </thead>
            <tbody>


              @php
              $suma_monto=0;
              @endphp

              
              @foreach ( $lconsolidado as $consolidado)
              <tr class="odd">
                <td class="dtr-control" tabindex="0">{{$consolidado->unidad_descripcion}}</td>
                <td>{{$consolidado->rubro_descripcion}}</td>
                <td>{{$consolidado->monto_consolidado}}</td>
                @php
                $suma_monto+=$consolidado->monto_consolidado;
                @endphp
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th rowspan="1" colspan="2">TOTAL BS.</th>
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