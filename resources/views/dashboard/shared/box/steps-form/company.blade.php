<?php
    /**
     * @var Gcr\Process $process
     * @var Gcr\Company|null $company
     */
?>
<h3>{{ $step['label'] }}</h3>
<section>
    @php
        $company = $process->company
    @endphp
    <input type="hidden" name="company[id]" value="{{ optional($company)->id }}">

    @if($process->isTransformation())
    <div class="row">
        <div class="form-group col-xs-12">
            <label for="transformation_with_change[]">Havendo alguma alteração além da transformação tipo jurídico, favor inserir/selecionar no campo abaixo?</label>
            <select id="transformation_with_change[]" name="transformation_with_change[]" class="form-control" multiple>
                @foreach(Gcr\Process::attributeOptions('fields_editing') as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-xs-12 col-md-3">
            <label for="company[name]">Nome Empresarial</label>
            <input id="company[name]" name="company[name]" type="text" class="form-control" value="{{ optional($company)->name }}">
        </div>

        @if(!$process->isCreating())
        <div class="form-group col-xs-12 col-md-3">
            <label for="company[nire]">NIRE @if ($process->isEditingSubsidiary()) (da filial) @endif</label>
            <input id="company[nire]" name="company[nire]" type="text" class="form-control" value="{{ optional($company)->nire }}" maxlength="11">
        </div>

        <div class="form-group col-xs-12 col-md-3">
            <label for="company[cnpj]">CNPJ @if ($process->isEditingSubsidiary()) (da filial) @endif</label>
            <input id="company[cnpj]" name="company[cnpj]" type="text" class="form-control cnpj" data-masked="00.000.000/0000-00" data-masked-reverse value="{{ optional($company)->cnpj }}">
        </div>
        @endif

        @if($process->isCreating())
        <div class="form-group col-xs-12 col-md-3">
            <label for="company[activity_start]">Data de início da atividade</label>
            <input id="company[activity_start]" name="company[activity_start]" type="date" class="form-control dataBr" value="{{ optional(optional($company)->activity_start)->toDateString() }}">
        </div>
        @endif
    </div>

    <div class="row">
        @if(!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompany() || $process->isEditingCapital()))
        <div class="form-group col-xs-12 col-md-4">
            <label for="company[share_capital]">Capital Social</label>
            <input id="company[share_capital]" name="company[share_capital]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse value="{{ optional($company)->share_capital }}">
            @if ($process->isEireli())
                <span id="company[share_capital]" class="help-block">A partir de 100 vezes o salário minimo</span>
            @endif
        </div>
        @endif

        @if (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompany() || $process->isEditingCompanySize()))
        <div class="form-group col-xs-12 col-md-4">
            <label for="company[size]">Porte da Empresa</label>
            <select id="company[size]" name="company[size]" class="form-control">
                @foreach(Gcr\Company::attributeOptions('size') as $value => $label)
                    <option value="{{ $value }}" @if($value == optional($company)->size) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="form-group col-xs-12 col-md-4">
            <label for="company[signed]">Data de Assinatura</label>
            <input id="company[signed]" name="company[signed]" type="date" class="form-control dataBr" value="{{ optional(optional($company)->signed)->toDateString() }}">
        </div>
    </div>

    @if (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyCnaes()))
    <div class="row">
        <div class="form-group col-xs-12">
            <label for="company[activity_description]">Descrição da Atividade</label>
            <textarea id="company[activity_description]" name="company[activity_description]" type="text" class="form-control">{{ optional($company)->activity_description }}</textarea>
        </div>
    </div>
    @endif

    @if (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyCnaes()))
        @component('dashboard.shared.box.steps-form.partials.cnae', [
            'step' => $step,
            'process' => $process,
            'cnaes' => optional($company)->cnaes,
            'type' => 'company',
        ])
        @endcomponent
    @endif

    @if (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyAddress() || $process->isEditingTransferToAnotherUf() || $process->isEditingTransferFromAnotherUfToSp()))
        @component('dashboard.shared.box.steps-form.partials.address', [
            'step' => $step,
            'company' => $company,
            'address' => optional($company)->address,
            'type' => 'company',
        ])
        @endcomponent
    @endif
</section>
