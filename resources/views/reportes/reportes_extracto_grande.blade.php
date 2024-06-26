@extends('layouts.master')
@section('contenido')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">

      <div class="card-body box-profile">

        <h3 class="profile-username text-center">REPORTE GENERAL DE TODO</h3>
        <p class="text-muted text-center">DESCRIPCIÓN</p>

        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">RUBRO</th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE DE UNIDAD</th>
                 @if ((Auth::user()->categoria)=='2')
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE DE LA CUENTA CLASIFICADORA</th>
              @endif
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">PR0DUCTO</th> 
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">RECIBO SERIE</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">FECHA DEL PAGO</th>  
                @if ((Auth::user()->categoria)=='2')
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">NRO DE RECIBO</th>  
                @endif
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">SECTOR</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">CANTIDAD</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">PRECIO POR UNIDAD/UNITARIO</th>  
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">MONTO</th>
              </tr>
            </thead>
            <tbody>


              @php
              $suma_monto=0;
              @endphp


              @foreach ( $lconsolidado as $consolidado)
              <tr class="odd">
                <td class="dtr-control" tabindex="0">{{$consolidado->rubro_descripcion}}</td>
                <td>{{$consolidado->unidad_descripcion}}</td>
                @if ((Auth::user()->categoria)=='2')
              
                <td>{{$consolidado->cuenta_clasificador_descripcion}}</td>
                @endif
                <td>{{$consolidado->producto}}</td>
                <td>{{$consolidado->serie}}</td>
                <td>{{$consolidado->fecha_pago}}</td>
                @if ((Auth::user()->categoria)=='2')
                <td>{{$consolidado->numero_recibo}}</td>
                @endif
                <td>{{$consolidado->sector}}</td>
                <td>{{$consolidado->cantidad}}</td>
                <td>{{$consolidado->precio_unitario}}</td>
                <td>{{$consolidado->monto}}</td>
                @php
                $suma_monto+=$consolidado->monto;
                @endphp
              </tr>
              @endforeach
            

            </tbody>
            <tfoot>
            @if ((Auth::user()->categoria)=='2')
              <tr>
                <th rowspan="1" colspan="10">TOTAL BS.</th>
                <th rowspan="1" colspan="1">{{$suma_monto}}</th>
              </tr>
             @else 
             @if ((Auth::user()->categoria)=='1')
               <tr>
                <th rowspan="1" colspan="8">TOTAL BS.</th>
                <th rowspan="1" colspan="1">{{$suma_monto}}</th>
              </tr>
              @endif
            @endif
            </tfoot>
          </table>
        </div>

        <a href="{{route('reportes.ajaxGetExtracto',['fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin, 'extracto_general'=>'extracto_general' ])}}" class="btn btn-primary btn-block"><b>IMPRIMIR</b></a>


      </div>
    </div>
  </div>
</div>
@endsection