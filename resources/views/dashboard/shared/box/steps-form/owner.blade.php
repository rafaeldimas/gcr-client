<h3>{{ $step['label'] }}</h3>
<section>
    @php
        $owner = $process->owner
    @endphp

    <input type="hidden" name="owner[id]" value="{{ !$owner ? '' : $owner->id }}">
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="owner[name]">Nome Completo</label>
            <input id="owner[name]" name="owner[name]" type="text" class="form-control" value="{{ !$owner ? '' : $owner->name}}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="owner[marital_status]">Estado Civil</label>
            <select id="owner[marital_status]" name="owner[marital_status]" class="form-control" value="{{ !$owner ? '' : $owner->marital_status }}">
                @foreach(['Solteiro', 'Casado', 'Divorciado', 'Viúvo', 'Separado'] as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
            <label for="owner[rg]">RG</label>
            <input id="owner[rg]" name="owner[rg]" type="text" class="form-control" value="{{ !$owner ? '' : $owner->rg }}">
        </div>

        <div class="form-group col-xs-12 col-md-4">
            <label for="owner[rg_expedition]">Data de Expedição</label>
            <input id="owner[rg_expedition]" name="owner[rg_expedition]" type="text" class="form-control" data-masked="00/00/0000" value="{{ !$owner ? '' : $owner->rg_expedition}}">
        </div>

        <div class="form-group col-xs-12 col-md-4">
            <label for="owner[cpf]">CPF</label>
            <input id="owner[cpf]" name="owner[cpf]" type="text" class="form-control" data-masked="000.000.000-00" data-masked-reverse value="{{ !$owner ? '' : $owner->cpf }}">
        </div>
    </div>

    @component('dashboard.shared.box.steps-form.partials.address', [
        'step' => $step,
        'address' => !$owner ? '' : $owner->address,
        'type' => 'owner',
    ])
    @endcomponent
</section>
