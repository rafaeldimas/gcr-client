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
            <form action="{{ route('dashboard.process.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="type">Tipo</label>
                        <select id="type" name="type" class="form-control">
                            @foreach(['creating' => 'Criar', 'updating' => 'Atualizar'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="type_company">Tipo de empresa</label>
                        <select id="type_company" name="type_company" class="form-control">
                            @foreach([
                                'businessman' => 'Empresário individual',
                                'society' => 'Sociedade Limitada',
                                'eireli' => 'Eireli',
                                'other' => 'Outros',
                            ] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label for="description">Breve Descrição</label>
                        <textarea id="description" name="description" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-default">Salvar</button>
            </form>
        @endslot
        @slot('footer')
        @endslot
    @endcomponent
@stop
