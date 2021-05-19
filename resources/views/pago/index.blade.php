

@extends('layouts.master')
@section('contenido')
    <div class="row">
      
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">               
            <h3>Listado de Pagos
           
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Numero de serie</th>                    
                    <th>Carnet de Identidad</th>                    
                    <th>Depositante</th>                   
                    <th>Fecha deposito</th>                   
                    <th>Total</th>                   
                    <th>Opciones</th>
                    </thead>
                    @foreach($lpagos as $pago)
                    <tr>
                        <td>{{$pago->getNumeroSerieStr()}}</td>                        
                        <td>{{$pago->cliente->ci." ".$pago->cliente->ci_expedido}}</td>                        
                        <td>{{$pago->cliente->nombres." ".$pago->cliente->apellidos}}</td>                        
                        <td>{{$pago->getFechaPagoStr()}}</td>                    
                        <td>{{$pago->total}}</td>                    
                        <td>
                        <a href="{{route('pago.show',$pago->id)}}"><button class="btn btn-primary">Ver</button></a>                      
                        <a href="#" ><button class="btn btn-primary">Anular</button></a>                        
                    </tr>
                  
                    @endforeach
                </table>
            </div>
            {{$lpagos->render()}}
        </div>
    </div>
@endsection