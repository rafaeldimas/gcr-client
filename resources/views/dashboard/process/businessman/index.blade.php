@extends('adminlte::page')

@section('title', 'Empresário individual')

@section('content_header')
    <h1>Empresário individual</h1>
@stop

@section('content')
    @component('dashboard.shared.box.grid.model', [
        'models' => $processes,
        'linkEdit' => 'dashboard.process.businessman.edit',
        'fields' => [
            'protocol',
            'user' => ['name'],
            'status',
        ]
    ])
        @slot('boxTitle')
            Lista de processos de Empresário individual
        @endslot
        @slot('gridHead')
            <tr>
                <th>Protocolo</th>
                <th>Usuário</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        @endslot
        @slot('boxTitle')
            Lista de processos de Empresário individual
        @endslot
    @endcomponent
@stop
