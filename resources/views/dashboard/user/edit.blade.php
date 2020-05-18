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
            <form action="{{ route('dashboard.user.update', [$user]) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <h1 class="text-center">Dados Contador</h1>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="name">Nome</label>
                        <input id="name" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="phone">Telefone</label>
                        <input id="phone" name="phone" data-masked="(00) 0000-0000" class="form-control" value="{{ $user->phone }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="mobile_phone">Celular</label>
                        <input id="mobile_phone" name="mobile_phone" data-masked="(00) 00000-0000" class="form-control" value="{{ $user->mobile_phone }}">
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" id="logo">
                    </div>

                    @if($user->logo)
                        <div class="col-xs-12 col-md-6">
                            <img src="{{ $user->logoUrl() }}" class="img-responsive img-thumbnail img-lg">
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="password">Repita a senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>

                <h1 class="text-center">Dados da Contabilidade</h1>

                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[name]">Nome</label>
                        <input id="accounting[name]" name="accounting[name]" class="form-control" value="{{ optional($user->accounting)->name }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email]">E-mail</label>
                        <input id="accounting[email]" name="accounting[email]" type="email" class="form-control" value="{{ optional($user->accounting)->email }}">
                    </div>
                </div>

                @component('dashboard.shared.box.steps-form.partials.address', [
                    'address' => optional($user->accounting)->address,
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
