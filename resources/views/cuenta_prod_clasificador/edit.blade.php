

@extends('layouts.master')
@section('contenido')

<div id="app">
<pago-prod-component pid_clasificador={{$id}} />
</div>

@endsection

@push('scripts')

@endpush