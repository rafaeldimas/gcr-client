@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    @component('dashboard.shared.box.grid.model', $gridData)
        @slot('boxTitle')
            Lista de processos de {{ $title }}
        @endslot
        @slot('gridHead')
            <tr>
                <th>Protocolo</th>
                <th>Usuário</th>
                <th>Editando</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        @endslot
    @endcomponent
@stop
