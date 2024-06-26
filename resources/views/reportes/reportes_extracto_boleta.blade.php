@extends('layouts.master')
@section('contenido')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">

      <div class="card-body box-profile">

        <h3 class="profile-username text-center">CLASIFICADO POR SERIE DE BOLETA </h3>
        <p class="text-muted text-center">DESCRIPCIÓN</p>

        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">CATEGORIA</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">NROº DE SERIE</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">RUBRO</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE DE UNIDAD</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE DE USUARIO</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">FECHA DEL PAGO</th>  
                 <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">TOTAL</th>  
              </tr>
            </thead>
            <tbody>


              @php
              $suma_monto=0;
              $prr=0;
              @endphp

              
              @foreach ( $lconsolidado as $consolidado)
              <tr class="odd">
                @if($consolidado->serie!=$prr)
                <td class="dtr-control" tabindex="0">{{$consolidado->categoria}}</td>
                <td>{{$consolidado->serie}}</td>
                <td>{{$consolidado->rubro_descripcion}}</td>
                <td>{{$consolidado->unidad_descripcion,}}</td>
                <td>{{$consolidado->name}}</td>
                <td>{{$consolidado->fecha_pago}}</td>
                <td>{{$consolidado->total}}</td>
                @php
                $suma_monto+=$consolidado->total;
                $prr=$consolidado->serie;
                @endphp

                @endif
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th rowspan="1" colspan="6">TOTAL BS.</th>
                <th rowspan="1" colspan="1">{{$suma_monto}}</th>
              </tr>
            </tfoot>
          </table>
        </div>

        <a href="{{route('reportes.ajaxGetExtracto',['fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin, 'extracto_boleta'=>'extracto_boleta' ])}}" class="btn btn-primary btn-block"><b>IMPRIMIR</b></a>


      </div>
    </div>
  </div>
</div>
@endsection