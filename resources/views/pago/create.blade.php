

@extends('layouts.master')
@section('contenido')
<div id="app">
<pago-component tipo_cuenta= {{Auth::user()->categoria}} />
</div>
@endsection

@push('scripts')

@endpush