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
                    <option value="Unidad_Rubro_Pago">Unidad con Rubro y Pago</option>
                    <option value="Unidad_Pago">Unidad y Pago</option>
                    <option value="Unidad_Clasificador_Pago">Unidad con Clasificador y Pago</option>
                    <option value="Unidad_Rubro_clasificador_Pago">Unidad con Rubro, Clasificador y Pago</option>
                    <option value="Clasificador_Pago">Clasificador y Pago</option>
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
            <a href="{{route('')}}" class="nav-link">
            </div>
        </form>
    </div>
</div>
@endsection