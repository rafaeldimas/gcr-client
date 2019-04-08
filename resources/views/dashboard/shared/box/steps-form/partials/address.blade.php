<div class="address">
    <input type="hidden" name="{{$type}}[address][id]" value="{{ !$address ? '' : $address->id }}">
    <h2 class="text-center">Endereço</h2>
    <div class="row">
        <div class="form-group col-xs-12 col-md-3">
            <label for="{{$type}}[address][postcode]">CEP</label>
            <input id="{{$type}}[address][postcode]" name="{{$type}}[address][postcode]" type="text" class="form-control postcode" placeholder="Preencha o cep" data-masked="00000-000" value="{{ !$address ? '' : $address->postcode }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$type}}[address][street]">Logradouro</label>
            <input id="{{$type}}[address][street]" name="{{$type}}[address][street]" type="text" class="form-control street" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->street }}">
        </div>
        <div class="form-group col-xs-12 col-md-3">
            <label for="{{$type}}[address][number]">Numero</label>
            <input id="{{$type}}[address][number]" name="{{$type}}[address][number]" type="text" class="form-control number" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->number }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$type}}[address][district]">Bairro</label>
            <input id="{{$type}}[address][district]" name="{{$type}}[address][district]" type="text" class="form-control district" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->district }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$type}}[address][city]">Cidade</label>
            <input id="{{$type}}[address][city]" name="{{$type}}[address][city]" type="text" class="form-control city" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->city }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$type}}[address][state]">Estado</label>
            <input id="{{$type}}[address][state]" name="{{$type}}[address][state]" type="text" class="form-control state" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->state }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="{{$type}}[address][country]">País</label>
            <input id="{{$type}}[address][country]" name="{{$type}}[address][country]" type="text" class="form-control country" placeholder="Preencha o cep" disabled value="{{ !$address ? '' : $address->country }}">
        </div>
    </div>
</div>
