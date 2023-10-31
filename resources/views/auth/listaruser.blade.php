@extends('layouts.master')
@section('contenido')

@if(Session::has('mensaje'))
<div class="modal fade" tabindex="-1" role="dialog" show_source  id="exitoso">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>{{ Session :: get('mensaje')}}<i class="em em-face_with_hand_over_mouth" style="font-size: 24px;" aria-role="presentation" aria-label=""></i></p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="cerrar_dlg()">Aceptar</button>
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> -->
      </div>
    </div>
  </div>
</div>
@endif


<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Usuarios<a href="venta/create">
                <a href="{{route('authreg')}}"><button class="btn btn-primary">Nuevo</button></a>
    </div>
</div>
<div class="row" id="app">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Numero</th>
                    <th>Nombre y Apellidos</th>
                    <th>Correo Electronico</th>
                    <th>Sexo</th>
                    <th>Tipo De Usuario</th>
                    <th>Area</th>
                    <th>Opciones</th>
                </thead>
                @php
                $aux=0;
                @endphp
                @foreach ($Usuario as $use)
                <tr>
                    <td>{{$aux=$aux+1}}</td>
                    <td>{{$use->name}}</td>
                    <td>{{$use->email}}</td>
                    <td>{{$use->sexo}}</td>
                    <td>{{$use->cargo}}</td>
                    @if($use->categoria==1)
                    <td>{{'Tesoreria'}} </td>
                     @else
                     <td> {{'Comercializacion'}} </td>
                     @endif   
                    <td>
                        <a href="{{route('usuario.detallado',$use->id)}}"><button class="btn btn-primary">Detalle</button></a>
                        <a href="{{route('usuario.edit',$use->id)}}"><button class="btn btn-primary">Editar</button></a>
                        <a href="{{route('prod_cuenta.create',['id'=>$use->id,'email'=>$use->email,'name'=>$use->name ])}}"><button class="btn btn-primary">Permisos-Venta</button></a>

                </tr>
                @endforeach
            </table>

                   <div class="container-fluid">
                      {!! $Usuario->links()!!}
                   </div> 
                 
           
        </div>
    </div>
</div>
@endsection
@push ('scripts')
<script>
id_anular=-1;
   $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // $("#error_dialogo").hide();
  });
    function mostrar_dialog(event){
    //  id_anular=id;
      console.log("mostrando dialog");
      $('#exitoso').modal('show');

    }
//     function anular_click(event){
   
//     $.ajax({
//         url: "{{route('pago.anular')}}",
//         type: 'post',
//         dataType: 'json',
//         data:{
//           id:id_anular,
//           "_token": "{!! csrf_token() !!}"
//         },
//         success: function(response) {
//            //let error=response.data.error;
//           //  alert(response.error_msg);
           
//            $("#error_dialogo_msg").html(response.error_msg);
//           //  $("#error_dialogo").removeClass("d-none");
//           // $("#error_dialogo").show();
//           $('#mensaje').modal('show');
        
//         }
//       });

//     $('#dialogo_anular').modal('hide');
//   }
  function cerrar_dlg(){
    $("#exitoso").hide(); 
     location.reload();
  }

 this.mostrar_dialog();

 
</script>

@endpush