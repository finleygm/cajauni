

@extends('layouts.master')
@section('contenido')
<div id="app">
    @if(Auth::user()->categoria==2)
    <h1 style="color: #808000; font-family: 'Times New Roman'; text-align: center; " ><strong>COMERCIALIZACION</strong></h1>
    
    @else
    <h1 style="color: #808000; font-family: 'Times New Roman'; text-align: center; " ><strong>TESORERIA</strong></h1>  
    @endif

<pago-component tipo_cuenta="{{Auth::user()->categoria}}" id_user="{{Auth::user()->id}}" />
</div>
@endsection

@push('scripts')

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

this.function();
</script>
@endpush