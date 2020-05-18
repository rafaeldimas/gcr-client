@extends('adminlte::master')

@section('adminlte_css')
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>

        <div class="login-box-body">
            <form action="{{ route('dashboard.user.first.time.login', $user) }}" method="post" enctype="multipart/form-data">
                @csrf

                <p class="login-box-msg">Informe sua nova senha</p>

                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}"
                           placeholder="Senha" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Repita a senha" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>

                <p class="login-box-msg">Complete as informações do cadastro</p>

                <div class="form-group has-feedback {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                           placeholder="Telefone" data-masked="(00) 0000-0000">
                    <span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('mobile_phone') ? 'has-error' : '' }}">
                    <input type="text" name="mobile_phone" class="form-control" value="{{ old('mobile_phone') }}"
                           placeholder="Celular" data-masked="(00) 00000-0000">
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    @if ($errors->has('mobile_phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('mobile_phone') }}</strong>
                        </span>
                    @endif
                </div>

                <p class="login-box-msg">Defina a logo</p>

                <div class="form-group has-feedback {{ $errors->has('logo') ? 'has-error' : '' }}">
                    <input type="file" name="logo" id="logo" class="form-control" value="{{ old('logo') }}" >
                    @if ($errors->has('logo'))
                        <span class="help-block">
                            <strong>{{ $errors->first('logo') }}</strong>
                        </span>
                    @endif
                </div>

                <p class="login-box-msg">Dados da Contabilidade</p>

                @php
                    $accounting = optional($user->accounting);
                    $address = optional($accounting->address);
                @endphp

                <div class="form-group has-feedback {{ $errors->has('accounting.name') ? 'has-error' : '' }}">
                    <input type="text" name="accounting[name]" class="form-control"
                           placeholder="Nome" value="{{ $accounting->name }}">
                    <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
                    @if ($errors->has('accounting.name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accounting.name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('accounting.email') ? 'has-error' : '' }}">
                    <input type="email" name="accounting[email]" class="form-control"
                           placeholder="E-mail" value="{{ $accounting->email }}" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('accounting.email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accounting.email') }}</strong>
                        </span>
                    @endif
                </div>

                <p class="login-box-msg">Endereço da Contabilidade</p>

                <div class="address">
                    <input type="hidden" name="accounting[address][id]" value="{{ $address->id }}">

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][postcode]" class="form-control postcode cep" data-masked="00000-000"
                               placeholder="CEP" value="{{ $address->postcode }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][street]" class="form-control street"
                               placeholder="Logradouro" value="{{ $address->street }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][number]" class="form-control number"
                               placeholder="Numero" value="{{ $address->number }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][district]" class="form-control district"
                               placeholder="Bairro" value="{{ $address->district }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][city]" class="form-control city"
                               placeholder="Cidade" value="{{ $address->city }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][state]" class="form-control state"
                               placeholder="Estado" value="{{ $address->state }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('accounting.address') ? 'has-error' : '' }}">
                        <input name="accounting[address][country]" class="form-control country"
                               placeholder="País" value="{{ $address->country }}">
                        <span class="glyphicon glyphicon-road form-control-feedback"></span>
                        @if ($errors->has('accounting.address'))
                            <span class="help-block">
                            <strong>{{ $errors->first('accounting.address') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit"
                                class="pull-right btn btn-primary btn-block btn-flat">Entrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

