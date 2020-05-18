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
                @can('admin')
                <div class="row">
                    <div class="form-group col-xs-12 col-md-3">
                        <label for="user_id">Usúario</label>
                        <select id="user_id" name="user_id" class="form-control">
                            @foreach(Gcr\User::all() as $user)
                                <option value="{{ $user->id }}" @if($user->id === (int) old('user_id')) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endcan

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="operation">Operação</label>
                        <select id="operation" name="operation" class="form-control">
                            @foreach(Gcr\Process::attributeOptions('operation') as $value => $label)
                                <option value="{{ $value }}" @if($value === (int) old('operation')) selected @endif>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="type_company">Atual Tipo Jurídico da empresa</label>
                        <select id="type_company" name="type_company" class="form-control">
                            <option value="" selected>Selecione</option>
                            @foreach(Gcr\Process::attributeOptions('type_company') as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-md-4 hidden fields_editing">
                        <label for="fields_editing[]">Campos que serão alterados</label>
                        <select id="fields_editing[]" name="fields_editing[]" class="form-control" multiple>
                            @foreach(Gcr\Process::attributeOptions('fields_editing') as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-md-4 hidden new_type_company">
                        <label for="new_type_company">Novo Tipo Jurídico da empresa</label>
                        <select id="new_type_company" name="new_type_company" class="form-control">
                            <option value="" selected>Selecione</option>
                            @foreach(Gcr\Process::attributeOptions('type_company') as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 hidden description_of_changes">
                        <label for="description_of_changes">Explique quais alterações serão feitas</label>
                        <textarea id="description_of_changes" name="description_of_changes" class="form-control" disabled></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12">
                        <label for="description">Observações e/ou resumo da solicitação</label>
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
