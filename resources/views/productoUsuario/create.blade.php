

@extends('layouts.master')
@section('contenido')

<div id="app">
<prod-cuenta-component id_user={{$id}} email={{$email}} name="{{$name}}"/>
</div>

@endsection

@push('scripts')

@endpush