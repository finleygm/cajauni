@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
      
         <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                             
                
                <div class="align-middle" style="width: 100%; display: flex; align-items: center; ">
                <img src="/unibol_logo.png" style="display:inline; width: 20%; height: 60px;" alt="">
                <div class="text-center align-middle" style="display: inline-flex; width: 80%;  float:  right; ">
                  <h3 style="width: 80% !important; font-weight: bold;" >RECIBO DE INGRESO</h3>
                </div>
              </div>
                <!-- <p class="text-muted text-center">DESCRIPCIÓN</p> -->
                <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">

                    <b>Nº SERIE </b> <a class="float-right">{{$pago->getNumeroSerieStr()}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>LUGAR Y FECHA </b> <a class="float-right">{{$pago->lugar." , ".$pago->getFechaPagoStr()}}</a>
                  </li>
                  @if($pago->sector === 'Externa')
                  <li class="list-group-item">
                    <b>NRO DE BOLETO</b> <a class="float-right">{{$pago->nro_recibo}}</a>
                  </li>
                  @endif
                  <li class="list-group-item">
                    <b>NOMBRE DE DEPOSITANTE</b> <a class="float-right">{{$pago->cliente->nombres." ".$pago->cliente->apellidos}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>CARNET DE INDENTIDAD</b> <a class="float-right">{{$pago->cliente->ci." ".$pago->cliente->ci_expedido}}</a>
                  </li>

                     <li class="list-group-item">
                    <b>USUARIO DE SISTEMA</b> <a class="float-right">{{$pago->user->email}}</a>
                  </li>
                
                  <!-- <li class="list-group-item">
                    <b>TOTAL </b> <a class="float-right">{{$pago->total}}</a>
                  </li> -->
                </ul>
                <div class="col-sm-12"><div class="table-responsive" >
                 <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                  <thead>
                  <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">Cantidad</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1"  aria-label="Browser: activate to sort column ascending">Descripcion Pago</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Valor Unitario</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Unidad</th>
                   <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column descending" aria-sort="ascending">Valor total</th>
                    
                 </tr>
                  </thead>
                  <tbody>   
                
                  @foreach ( $pago->detalle_pago as $pago_detalle)
                    <tr class="odd">
                    <td class="dtr-control" tabindex="0">{{$pago_detalle->cantidad}}</td>
                    <td>{{$pago_detalle->descripcion}}</td>
                    <td class="">{{$pago_detalle->precio_unitario}}</td>
                    <td> {{$pago_detalle->cuenta->unidad}}  </td>             
                    <td class="sorting_1">{{number_format($pago_detalle->monto, 2, '.', ',')}}</td>
                    
                  </tr>
                   @endforeach              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th rowspan="1" colspan="1">TOTAL BS.</th>
                    <th rowspan="1" colspan="3">{{$pago->getTotalALiteral()}}</th>                    
                    <th rowspan="1" colspan="1">{{number_format($pago->total, 2, '.', ',')}}</th>
                  </tr>
                  </tfoot>
                </table></div></div>
                <!-- <a href="{{route('pago.getDocument',$pago->id)}}" class="btn btn-primary btn-block no-print"><b>IMPRIMIR</b></a> -->
              <boton class="btn btn-primary btn-block no-print" id="boton-imprimir"> <b>IMPRIMIR</b></boton>
          
              @if( $pago->estado_pago == 'Anulado')
            </div>  
            <!-- <img src="/anulado2.png" class="marca-de-agua" style="position: absolute; bottom: 10px; top: 50px; right: 190px; opacity: 0.5; "> -->
            <p style="
            opacity: 0.5; 
            font-size: 70px; 
            transform: rotate(-45deg); 
            white-space: nowrap; 
            font-weight: bold;
            left: 270px;
            top: 320px;
            position: absolute;
            transform-origin: top left;">ANULADO</p>
           
            <p style=" font-size: 20px; transform: rotate(-45deg); white-space: nowrap; font-weight: bold; left: 380px; top: 330px; position: absolute;  transform-origin: top left;"> {{$pago['fecha_anulacion']}} </p>
            @endif
            </div>   
           
            <br><br><br>
      <!-- <div style="margin-left:100px;"> -->

             

                  <table class="print">
                    <thead>
                      <tbody>
                      <tr>
                        <th id="reci" >RECIBE CONFORME</th>
                        <th id="entre" >ENTREGA CONFORME</th>
                      </tr>
                    </thead>
                    </tbody>
                  </table>
               
             <!-- </div>          -->
        </div>
    </div>
@endsection

@push ('scripts')
<script>

  var re=document.getElementById('reci');
  var en=document.getElementById('entre');
  var boton = document.querySelector('.print');


 
  document.getElementById('boton-imprimir').addEventListener('click', function() {
   
    boton.style.width = '100%';
    //boton.style.textAlign='center'; 
     boton.style.display = 'inline'; 
      boton.style.margin = '0 auto'; 
    en.style.textAlign = 'right'; 
    en.style.width = '900px'; 
    re.style.width = '200px'; 
     re.style.textAlign = 'start';
        window.print();
        window.
        boton.style.display = 'none'; 
    
      });


        
</script>

@endpush