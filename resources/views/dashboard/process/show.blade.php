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
                <th>Descrição</th>
                <th>Data</th>
            </tr>
        @endslot
        @slot('gridBody')
            @foreach($statuses as $status)
                <tr style="background-color: {{ $status->color }};">
                    <td>{{ $status->label }}</td>
                    <td>{{ $status->pivot->description }}</td>
                    <td>{{ $status->pivot->updated_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        @endslot
        @slot('boxFooter')
        @endslot
    @endcomponent

    @can('admin')
        <form action="{{ route('dashboard.process.update.status', $process) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Adicionar novo status ao processo</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    @foreach(Gcr\Status::all() as $status)
                                        <option value="{{ $status->id }}">{{ $status->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Descrição do Status</label>
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>

                            <button class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endcan
@stop
