@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">                
                </div>
                <h3 class="profile-username text-center">INFORMACIÓN DE PAGO</h3>
                <p class="text-muted text-center">DESCRIPCIÓN</p>
                <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">

                    <b>Nº SERIE </b> <a class="float-right">{{$pago->getNumeroSerieStr()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>LUGAR Y FECHA </b> <a class="float-right">{{$pago->lugar." , ".$pago->getFechaPagoStr()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>NOMBRE DE DEPOSITANTE</b> <a class="float-right">{{$pago->cliente->nombres." ".$pago->cliente->apellidos}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>CARNET DE INDENTIDAD</b> <a class="float-right">{{$pago->cliente->ci." ".$pago->cliente->ci_expedido}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>PAGO POR CONCEPTO</b> <a class="float-right">{{$pago->cuenta_clasificador->descripcion}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>USUARIO DE SISTEMA</b> <a class="float-right">{{$pago->user->email}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>TOTAL </b> <a class="float-right">{{$pago->total}}</a>
                  </li>
                </ul>
                <div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                  <thead>
                  <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">Cantidad</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1"  aria-label="Browser: activate to sort column ascending">Descripcion Pago</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Valor Unitario</th>
                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column descending" aria-sort="ascending">Valor total</th>
                    
                 </tr>
                  </thead>
                  <tbody>   
                
                  @foreach ( $pago->detalle_pago as $pago_detalle)
                    <tr class="odd">
                    <td class="dtr-control" tabindex="0">{{$pago_detalle->cantidad}}</td>
                    <td>{{$pago_detalle->descripcion}}</td>
                    <td class="">{{$pago_detalle->precio_unitario}}</td>
                    <td class="sorting_1">{{number_format($pago_detalle->monto, 2, '.', ',')}}</td>
                    
                  </tr>
                   @endforeach              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th rowspan="1" colspan="1">TOTAL BS.</th>
                    <th rowspan="1" colspan="2">{{$pago->getTotalALiteral()}}</th>                    
                    <th rowspan="1" colspan="1">{{number_format($pago->total, 2, '.', ',')}}</th>
                  </tr>
                  </tfoot>
                </table></div>
                <a href="{{route('pago.getBoleta',$pago->id)}}" class="btn btn-primary btn-block"><b>IMPRIMIR</b></a>
              </div>
            </div>              
        </div>
    </div>
@endsection