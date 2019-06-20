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

    @component('dashboard.shared.box')
        @slot('title')
            {{ $title }}
        @endslot
        @slot('body')
            <form action="{{ route('dashboard.status.update', [$status]) }}" method="post">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="form-group col-xs-12 col-md-3">
                        <label for="label">Nome</label>
                        <input type="text" id="label" name="label" class="form-control" value="{{ $status->label }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="color">Cor</label>
                        <input type="color" id="color" name="color" class="form-control" value="{{ $status->color }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="text_white" value="1" {{ $status->text_white ? 'checked' : '' }}> Link Branco?
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-default">Salvar</button>
            </form>
        @endslot
        @slot('footer')
        @endslot
    @endcomponent
@stop
