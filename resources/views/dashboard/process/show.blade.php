@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    @component('dashboard.shared.box.grid')
        @slot('boxTitle')
            {{ $title }}
        @endslot
        @slot('gridHead')
            <tr>
                <th>Status</th>
                <th>Data</th>
            </tr>
        @endslot
        @slot('gridBody')
            @foreach($statuses as $status)
                <tr style="background-color: {{ $status->color }};">
                    <td>{{ $status->label }}</td>
                    <td>{{ $status->pivot->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        @endslot
        @slot('boxFooter')
        @endslot
    @endcomponent
@stop
