@extends('layouts.master')
@section('contenido')  
    <div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">                
                </div>
                <h3 class="profile-username text-center">CONSOLIDADO POR RUBRO</h3>
                <p class="text-muted text-center">DESCRIPCIÓN</p>
                
                <div class="col-sm-12">
                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                  <thead>
                  <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">RUBRO</th>
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">NOMBRE RUBRO</th>                                        
                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">MONTO</th>                                        
                 </tr>
                  </thead>
                  <tbody>                
                   @php
                   $suma_monto=0;
                   @endphp
                  @foreach ( $lconsolidado_rubro as $consolidado_rubro)
                    <tr class="odd">                      
                      <td class="dtr-control" tabindex="0">{{$consolidado_rubro->numero_identificador}}</td>
                      <td>{{$consolidado_rubro->rubro_descripcion}}</td>                    
                      <td>{{$consolidado_rubro->monto_consolidado}}</td>
                    @php
                     $suma_monto+=$consolidado_rubro->monto_consolidado;
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
                </table></div>
                
              </div>
            </div>              
        </div>
    </div>
@endsection