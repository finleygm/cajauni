
@extends('layouts.master')
@section('contenido')  
<div class="col-md-6">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">CONSOLIDADO POR RUBRO</h3>
              </div>            
              <form  action="{{route('reportes.ajaxGetRubro')}}"method="POST"  id="form-cuenta">
                @csrf 
                <div class="card-body">                   
                    <div class="form-group">
                      <label for="ifecha_ini">Fecha Inicio</label>
                      <input type="text" class="form-control" id="ifecha_ini" placeholder="Fecha Inicio" name="fecha_ini" value="{{old('fecha_ini','01/04/2021')}}">
                    </div>
                    <div class="form-group">
                      <label for="ifecha_fin">Fecha Final</label>
                      <input type="text" class="form-control" id="ifecha_fin" placeholder="Fecha Fin" name="fecha_fin" value="{{old('fecha_fin','31/05/2021')}}">
                    </div>                    
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">CALCULAR</button>
                </div>
              </form>
         </div>        
</div>
@endsection
