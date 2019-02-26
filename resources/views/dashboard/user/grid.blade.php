@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    @component('dashboard.shared.box.grid.model', $gridData)
        @slot('boxTitle')
            Lista de {{ $title }}
        @endslot
        @slot('gridHead')
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Tipo</th>
                <th>Número de processos</th>
            </tr>
        @endslot
    @endcomponent
@stop
