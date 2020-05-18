@php
    $prefixName = in_array($type, [ 'owners', 'subsidiaries' ]) ? "{$type}[{$parentId}][address]" : "{$type}[address]"
@endphp

<div class="address">
    <input type="hidden" name="{{$prefixName}}[id]" value="{{ optional($address)->id }}">
    <h2 class="text-center">Endereço</h2>
    <div class="row">
        <div class="form-group col-xs-12 col-md-3">
            <label for="{{$prefixName}}[postcode]">CEP</label>
            <input id="{{$prefixName}}[postcode]" name="{{$prefixName}}[postcode]" type="text" class="form-control postcode cep" placeholder="Preencha o cep" data-masked="00000-000" value="{{ optional($address)->postcode }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$prefixName}}[street]">Logradouro</label>
            <input id="{{$prefixName}}[street]" name="{{$prefixName}}[street]" type="text" class="form-control street" placeholder="Preencha o cep" disabled value="{{ optional($address)->street }}">
        </div>
        <div class="form-group col-xs-12 col-md-3">
            <label for="{{$prefixName}}[number]">Numero</label>
            <input id="{{$prefixName}}[number]" name="{{$prefixName}}[number]" type="text" class="form-control number" placeholder="Preencha o cep" disabled value="{{ optional($address)->number }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$prefixName}}[district]">Bairro</label>
            <input id="{{$prefixName}}[district]" name="{{$prefixName}}[district]" type="text" class="form-control district" placeholder="Preencha o cep" disabled value="{{ optional($address)->district }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$prefixName}}[city]">Cidade</label>
            <input id="{{$prefixName}}[city]" name="{{$prefixName}}[city]" type="text" class="form-control city" placeholder="Preencha o cep" disabled value="{{ optional($address)->city }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$prefixName}}[state]">Estado</label>
            <input id="{{$prefixName}}[state]" name="{{$prefixName}}[state]" type="text" class="form-control state" placeholder="Preencha o cep" disabled value="{{ optional($address)->state }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$prefixName}}[country]">País</label>
            <input id="{{$prefixName}}[country]" name="{{$prefixName}}[country]" type="text" class="form-control country" placeholder="Preencha o cep" disabled value="{{ optional($address)->country }}">
        </div>
    </div>
</div>
