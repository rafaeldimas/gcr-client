@if (!$process->isUpdating() || ($process->isEditingCompany() || $process->isEditingCompanyCnaes() || $process->isEditingCompanyAddress()))
    <h3>{{ $step['label'] }}</h3>
    <section>
        @php
            $company = $process->company
        @endphp

        @if (!$process->isUpdating() || $process->isEditingCompany())
            <input type="hidden" name="company[id]" value="{{ !$company ? '' : $company->id }}">
            <div class="row">
                <div class="form-group col-xs-12 col-md-6">
                    <label for="company[name]">Nome Empresarial</label>
                    <input id="company[name]" name="company[name]" type="text" class="form-control" value="{{ !$company ? '' : $company->name }}">
                </div>

                <div class="form-group col-xs-12 col-md-6">
                    <label for="company[share_capital]">Capital Social</label>
                    <input id="company[share_capital]" name="company[share_capital]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse value="{{ !$company ? '' : $company->share_capital }}">
                    @if ($process->isEireli())
                        <span id="company[share_capital]" class="help-block">A partir de 100 vezes o salário minimo</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-md-6">
                    <label for="company[size]">Porte da Empresa</label>
                    <select id="company[size]" name="company[size]" class="form-control" value="{{ !$company ? '' : $company->size }}">
                        @foreach(Gcr\Company::attributeOptions('size') as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-xs-12 col-md-6">
                    <label for="company[signed]">Data de Assinatura</label>
                    <input id="company[signed]" name="company[signed]" type="date" class="form-control dataBr" value="{{ ($company && $company->signed) ? $company->signed->toDateString() : '' }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12">
                    <label for="company[activity_description]">Descrição da Atividade</label>
                    <textarea id="company[activity_description]" name="company[activity_description]" type="text" class="form-control">{{ !$company ? '' : $company->activity_description }}</textarea>
                </div>
            </div>
        @endif

        @if (!$process->isUpdating() || $process->isEditingCompanyCnaes())
            @component('dashboard.shared.box.steps-form.partials.cnae', [
                'step' => $step,
                'process' => $process,
                'cnaes' => !$company ? '' : $company->cnaes,
            ])
            @endcomponent
        @endif

        @if (!$process->isUpdating() || $process->isEditingCompanyAddress())
            @component('dashboard.shared.box.steps-form.partials.address', [
                'step' => $step,
                'company' => $company,
                'address' => !$company ? '' : $company->address,
                'type' => 'company',
            ])
            @endcomponent
        @endif
    </section>
@endif
