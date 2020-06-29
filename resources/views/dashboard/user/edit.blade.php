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
                        <label for="accounting[email_1]">E-mail 1</label>
                        <input id="accounting[email_1]" name="accounting[email_1]" type="email" class="form-control" value="{{ optional($user->accounting)->email_1 }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email_2]">E-mail 2</label>
                        <input id="accounting[email_2]" name="accounting[email_2]" type="email" class="form-control" value="{{ optional($user->accounting)->email_2 }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email_3]">E-mail 3</label>
                        <input id="accounting[email_3]" name="accounting[email_3]" type="email" class="form-control" value="{{ optional($user->accounting)->email_3 }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email_4]">E-mail 4</label>
                        <input id="accounting[email_4]" name="accounting[email_4]" type="email" class="form-control" value="{{ optional($user->accounting)->email_4 }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="accounting[email_5]">E-mail 5</label>
                        <input id="accounting[email_5]" name="accounting[email_5]" type="email" class="form-control" value="{{ optional($user->accounting)->email_5 }}">
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
