@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @component('dashboard.shared.box.grid.model', $gridData)
        @slot('boxTitle')
            Lista de {{ $title }}
        @endslot
        @slot('gridHead')
            <tr>
                <th>Nome</th>
                <th>Color</th>
                <th>Link Branco</th>
                <th>Ações</th>
            </tr>
        @endslot
    @endcomponent
@stop
