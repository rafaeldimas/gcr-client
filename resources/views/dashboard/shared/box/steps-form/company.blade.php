<h3>{{ $step['label'] }}</h3>
<section>
    @php
        $company = $process->company
    @endphp

    <input type="hidden" name="company[id]" value="{{ !$company ? '' : $company->id }}">
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="company[name]">Nome</label>
            <input id="company[name]" name="company[name]" type="text" class="form-control" value="{{ !$company ? '' : $company->name }}">
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="company[share_capital]">Capital Social</label>
            <input id="company[share_capital]" name="company[share_capital]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse value="{{ !$company ? '' : $company->share_capital }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-6">
            <label for="company[size]">Porte da Empresa</label>
            <select id="company[size]" name="company[size]" class="form-control" value="{{ !$company ? '' : $company->size }}">
                @foreach(['Microempresa', 'Pequeno', 'Médio', 'Grande'] as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-xs-12 col-md-6">
            <label for="company[signed]">Data de Assinatura</label>
            <input id="company[signed]" name="company[signed]" type="text" class="form-control" data-masked="00/00/0000" value="{{ !$company ? '' : $company->signed }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12">
            <label for="company[activity_description]">Descrição da Atividade</label>
            <textarea id="company[activity_description]" name="company[activity_description]" type="text" class="form-control">
                {{ !$company ? '' : $company->activity_description }}
            </textarea>
        </div>
    </div>

    @component('dashboard.shared.box.steps-form.partials.address', [
        'step' => $step,
        'address' => !$company ? '' : $company->address,
        'type' => 'company',
    ])
    @endcomponent
</section>
