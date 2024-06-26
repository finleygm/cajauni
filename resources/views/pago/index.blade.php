

@extends('layouts.master')
@section('contenido')
@if(session('mensaje'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      {{ session('mensaje') }}<i class="em em-face_palm" aria-role="presentation" aria-label="FACE PALM"></i>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
@endif
<!-- @if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session :: get('mensaje')}}
    <i class="em em-full_moon_with_face" aria-role="presentation" aria-label=""></i>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ Session :: get('error')}}
    <i class="em em-face_with_rolling_eyes" aria-role="presentation" aria-label=""></i>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>

@endif -->
<form action="{{route('pago.index')}}">
<div class="input-group">
  <div class="form-outline" data-mdb-input-init>
     <label class="form-label" for="form1">BUSCAR</label>
     <input type="search"  placeholder="CI o SERIE" name="busq" id="form1" class="form-control" />
   
  </div>
  <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
    <i class="fas fa-search"></i>
  </button>
</div>
</form>
<BR>
<div class="modal fade" tabindex="-1" role="dialog"  id="introduccion_guia">
  <div class="modal-dialog" role="document">
    <div class="modal-content" ">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                
              <p id="error_dialogo_msg" class="d-inline"> Es tu primera ves aqui?<i class="em em-upside_down_face" aria-role="presentation" style="font-size: 30px;" aria-label=""></i></p>
              <!-- <p id="error_dialogo_msg" class="d-inline"> Dejame Guiarte </p> -->
              <!-- <i class="em em-slightly_smiling_face" aria-role="presentation" style="font-size: 24px;" aria-label=""></i> -->
               <!-- <p>Estas seguro que desea anular. <i class="em em-cold_sweat" style="font-size: 24px;" aria-role="presentation" aria-label=""></i></p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  onclick="cerrar()" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>




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
                    <th>Estado de pago</th>                    
                    <th>Opciones</th>
                    </thead>
 


                    @foreach($lpagos as $pago)
                    <tr>
                    @if ($pago->categoria==1 and (Auth::user()->categoria)!='2')
                   
                        <td>{{$pago->getNumeroSerieStr()}}</td>                        
                        <td>{{$pago->cliente->ci." ".$pago->cliente->ci_expedido}}</td>                        
                        <td>{{$pago->cliente->nombres." ".$pago->cliente->apellidos}}</td>                        
                        <td>{{$pago->getFechaPagoStr()}}</td>                              
                        <td>{{$pago->total}}</td>     
                        <td>
                          @if($pago->estado_pago == 'Anulado')
                          <del>
                          {{$pago->estado_pago}}</del>
                          @else
                          {{$pago->estado_pago}}
                          @endif
                        </td>                
                        <td>
                        <a href="{{route('pago.show',$pago->id)}}"><button class="btn btn-primary">Ver</button></a>       
                        @if($pago->estado_pago!='Anulado')               
                            <button id="anular" class="btn btn-danger" onclick="mostrar_dialog(event,{{$pago->id}})" >Anular</button>  
                            @else
                            <button id="anular" class="btn btn-dark" style="background-color: darkgray;" disabled="true" onclick="mostrar_dialog(event,{{$pago->id}})" >Anular</button>  
                        @endif   
                      </tr>

                      @else
                      @if ($pago->categoria==2 and (Auth::user()->categoria)=='2')
                   
                   <td>{{$pago->getNumeroSerieStr()}}</td>                        
                   <td>{{$pago->cliente->ci." ".$pago->cliente->ci_expedido}}</td>                        
                   <td>{{$pago->cliente->nombres." ".$pago->cliente->apellidos}}</td>                        
                   <td>{{$pago->getFechaPagoStr()}}</td>                              
                   <td>{{$pago->total}}</td>     
                   <td>
                     @if($pago->estado_pago == 'Anulado')
                     <del>
                     {{$pago->estado_pago}}</del>
                     @elseuy 0
                     {{$pago->estado_pago}}
                     @endif
                   </td>                
                   <td>
                   <a href="{{route('pago.show',$pago->id)}}"><button class="btn btn-primary">Ver</button></a>       
                   @if($pago->estado_pago!='Anulado')               
                       <button id="anular" class="btn btn-danger" onclick="mostrar_dialog(event,{{$pago->id}})" >Anular</button>  
                       @else
                       <button id="anular" class="btn btn-dark" style="background-color: darkgray;" disabled="true" onclick="mostrar_dialog(event,{{$pago->id}})" >Anular</button>  
                   @endif   
                  </tr>
                   @endif 

                  @endif 
                    @endforeach
                </table>
            </div>
            {{$lpagos->render()}}
        </div>
    </div>

  <div class="modal fade" tabindex="-1" role="dialog"  id="mensaje">
  <div class="modal-dialog" role="document">
    <div class="modal-content" ">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color: aquamarine;">
                
              <p id="error_dialogo_msg" class="d-inline">Has anulado correctamente, espero ayas hecho lo correpto</p>
              <i class="em em-slightly_smiling_face" aria-role="presentation" style="font-size: 24px;" aria-label=""></i>
               <!-- <p>Estas seguro que desea anular. <i class="em em-cold_sweat" style="font-size: 24px;" aria-role="presentation" aria-label=""></i></p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  onclick="cerrar_dlg()" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Justificacion -->
<div class="modal fade" tabindex="-1" role="dialog"  id="dialogo_justificacion">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <label for="exampleFormControlTextarea1" class="form-label">Justifique Anulacion</label>
         <textarea class="form-control" id="justificacion" rows="3"></textarea> 
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="anular_click(event)">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- pregunta -->

  <div class="modal fade" tabindex="-1" role="dialog"  id="dialogo_anular">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Estas seguro que desea anular? <i class="em em-cold_sweat" style="font-size: 24px;" aria-role="presentation" aria-label=""></i></p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="mostrar_dialog_just()">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push ('scripts')
<script>
  let id_anular=-1;
  let just="";
   $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // $("#error_dialogo").hide();
  });
    function mostrar_dialog(event, id){
      id_anular=id;
      console.log("mostrando dialog");
      $('#dialogo_anular').modal('show');

    }

    function mostrar_dialog_just(event){
     // id_anular=id;
      console.log("mostrando dialog jsut");
      $('#dialogo_anular').modal('hide');
      $('#dialogo_justificacion').modal('show');
    }

    function anular_click(event){
   
    $.ajax({
        url: "{{route('pago.anular')}}",
        type: 'post',
        dataType: 'json',
        data:{
          id:id_anular,
      //    just:'justificacion',
         // justifica:this.justificac,
        justifica : document.getElementById("justificacion").value,
          "_token": "{!! csrf_token() !!}"
        },
        success: function(response) {
           //let error=response.data.error;
          //  alert(response.error_msg);
           
           $("#error_dialogo_msg").html(response.error_msg);
          //  $("#error_dialogo").removeClass("d-none");
          // $("#error_dialogo").show();
          $('#mensaje').modal('show');
        
        }
      });

      $('#dialogo_justificacion').modal('hide');
    $('#dialogo_anular').modal('hide');
  }
  function cerrar_dlg(){
    $("#error_dialogo").hide(); 
     location.reload();
  }

  function cerrar(){
    $("#introduccion_guia").hide(); 
    // location.reload();
  }
  function mostrar_guia(event){
      //console.log("mostrando dialog");
      $('#introduccion_guia').modal('show');
    }

 //   this.mostrar_guia();

 
</script>

@endpush