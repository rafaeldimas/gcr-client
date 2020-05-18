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
            <form action="{{ route('dashboard.user.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <h1 class="text-center">Dados Contador</h1>

                <div class="row">
                    @can('admin')
                        <div class="form-group col-xs-12 col-md-4">
                            <label for="type">Tipo</label>
                            <select type="text" id="type" name="type" class="form-control">
                                @foreach(Gcr\User::attributeOptions('type') as $value => $label)
                                    <option value="{{ $value }}" @if($value === (int) old('type')) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan

                    <div class="form-group col-xs-12 col-md-4">
                        <label for="name">Nome</label>
                        <input id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-4">
                        <label for="phone">Telefone</label>
                        <input id="phone" name="phone" data-masked="(00) 0000-0000" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label for="mobile_phone">Celular</label>
                        <input id="mobile_phone" name="mobile_phone" data-masked="(00) 00000-0000" class="form-control" value="{{ old('mobile_phone') }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-4">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" id="logo">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="password">Repita a senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <h1 class="text-center">Dados da Contabilidade</h1>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[name]">Nome</label>
                        <input id="accounting[name]" name="accounting[name]" class="form-control" value="{{ old('accounting.name') }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email]">E-mail</label>
                        <input id="accounting[email]" name="accounting[email]" type="email" class="form-control" value="{{ old('accounting.email') }}" required>
                    </div>
                </div>

                @component('dashboard.shared.box.steps-form.partials.address', [
                    'address' => false,
                    'type' => 'accounting',
                ])
                @endcomponent

                <button type="submit" class="btn btn-default">Salvar</button>
            </form>
        @endslot
        @slot('footer')
        @endslot
    @endcomponent
@stop
