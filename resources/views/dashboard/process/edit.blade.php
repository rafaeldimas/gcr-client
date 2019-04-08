@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    @component('dashboard.shared.box.multi-step-form', compact('title', 'steps', 'process'))
    @endcomponent
@stop
