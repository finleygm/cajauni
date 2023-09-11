@extends('layouts.master')

@section('contenido')
<BR>
@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session :: get('mensaje')}}

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif
</BR>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="fw-bold" style="text-align:center">BIENVENIDO</h1>

                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <img src="{{ asset('/storage/uploads/cja.png') }}" class="rounded mx-auto d-block" width="100%" alt="User Image">
                    <br>
                    Sistema de Caja Tesoreria
                </div>
            </div>
        </div>
    </div>
</div>
@endsection