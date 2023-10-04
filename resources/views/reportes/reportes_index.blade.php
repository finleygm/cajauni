@extends('layouts.master')
@section('contenido')
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">REPORTES</h3>
        </div>
        <form action="{{route('reportes.recoger')}}" method="POST" mid="form-cuenta">
            @csrf

            <div class="card-body col-md-6" >
                <select class="form-control" name="reportes" id="reportes" required autocomplete="reportes" aria-label="Large select example">
                    <option selected>Seleccionar Reporte</option>
                    <option value="Rubro_Unidad">Rubro con Unidad</option>
                    <option value="Extracto_Boleta">Extracto Por Boleta</option>
                    <option value="Rubro_Clasificador">Rubro Clasificador</option>
                    <option value="Extracto_General">Extracto General</option>
                </select>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="ifecha_ini">Indique Fecha Inicio</label>
                    <input type="text" class="form-control" id="ifecha_ini" placeholder="Fecha Inicio" name="fecha_ini" value="{{old('fecha_ini','01/04/2021')}}">
                </div>
                <div class="form-group">
                    <label for="ifecha_fin">Indique Fecha Final</label>
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