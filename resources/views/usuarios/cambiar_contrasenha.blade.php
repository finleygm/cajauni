

@extends('layouts.master')
@section('contenido')  
    <div class="row">
    @if (count($errors)>0)
                              
                <div id="message" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content bg-danger">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> INFORMACIÓN</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                           <div class="alert" >
                              <ul>
                                  @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                  @endforeach
                              </ul>
                           </div> 
                            
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>                          
                        </div>
                      </div>
                    </div>
              </div> 
        @endif
    <div class="col-md-6">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editar</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="{{route('usuario.actualizar_contrasenha',$usuario->id)}}" method="POST"  mid="form-cuenta">
              @csrf 
              {{ method_field('PUT') }}
              <div class="card-body">
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Contraseña Actual" name="old_password" value="">
                  </div>
                  <div class="form-group">
                    <label for="new_password">Password</label>
                    <input type="password" class="form-control" id="new_password" placeholder="Nueva contraseña" name="new_password" value="">
                  </div>
                  <div class="form-group">
                    <label for="password_confirm">Confirmar Password</label>
                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirmar Nueva contraseña" name="confirm_password" value="">
                  </div>                          
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                </div>
              </form>
            </div>        
        </div>
    </div>
@endsection

@push('scripts')
@if (count($errors)>0)
<script>
$(this).ready(function(){
  $('#message').modal('show')
});
</script>
@endif
@endpush