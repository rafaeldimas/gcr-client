<?php /** @var Gcr\Owner $owner */ ?>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="tab-owner-{{ $key }}">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#owners" href="#tab-content-owner-{{ $key }}" aria-expanded="false" aria-controls="tab-content-owner-{{ $key }}">
                {{ $step['label'] }} #{{ $key }}
            </a>
        </h4>
    </div>
    <div id="tab-content-owner-{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tab-owner-{{ $key }}">
        <div class="panel-body">
                <input type="hidden" name="owners[{{ $key }}][id]" value="{{ !$owner ? '' : $owner->id }}">

                @if (!$process->isBusinessman())
                    <div class="row">
                        <div class="form-group col-xs-12 {{ ($owner && $owner->showJobRolesOther()) ? 'col-md-6' : 'col-md-12' }}">
                            <label for="owners[{{ $key }}][job_roles][]">Cargos</label>
                            <select id="owners[{{ $key }}][job_roles][]" name="owners[{{ $key }}][job_roles][]" class="form-control" data-s2 multiple>
                                @foreach(Gcr\Owner::attributeOptions('job_roles') as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        {{ ($owner && $owner->containJobRole($value)) ? 'selected' : '' }}>
                                            {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-xs-12 col-md-6 {{ ($owner && $owner->showJobRolesOther()) ? '' : 'hidden' }}">
                            <label for="owners[{{ $key }}][job_roles_other]">Escreva o Cargo</label>
                            <input id="owners[{{ $key }}][job_roles_other]" name="owners[{{ $key }}][job_roles_other]" type="text" class="form-control" value="{{ !$owner ? '' : $owner->job_roles_other}}" {{ ($owner && $owner->showJobRolesOther()) ? '' : 'disable' }}>
                        </div>
                    </div>
                @endif

                <div class="row">

                    <div class="form-group col-xs-12 col-md-6">
                        <label for="owners[{{ $key }}][name]">Nome Completo</label>
                        <input id="owners[{{ $key }}][name]" name="owners[{{ $key }}][name]" type="text" class="form-control" value="{{ !$owner ? '' : $owner->name}}">
                    </div>

                    <div class="form-group col-xs-12 {{ ($owner && $owner->showWeddingWegime()) ? 'col-md-3' : 'col-md-6' }}">
                        <label for="owners[{{ $key }}][marital_status]">Estado Civil</label>
                        <select id="owners[{{ $key }}][marital_status]" name="owners[{{ $key }}][marital_status]" class="form-control" data-s2 value="{{ !$owner ? '' : $owner->marital_status }}">
                            @foreach(Gcr\Owner::attributeOptions('marital_status') as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    {{ ($owner && $owner->marital_status === $value) ? 'selected' : '' }}>
                                        {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-md-3 {{ ($owner && $owner->showWeddingWegime()) ? '' : 'hidden' }}">
                        <label for="owners[{{ $key }}][wedding_regime]">Regime de Casamento</label>
                        <select id="owners[{{ $key }}][wedding_regime]" name="owners[{{ $key }}][wedding_regime]" class="form-control" data-s2 value="{{ !$owner ? '' : $owner->wedding_regime }}" {{ ($owner && $owner->showWeddingWegime()) ? '' : 'disable' }}>
                            @foreach(Gcr\Owner::attributeOptions('wedding_regime') as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    {{ ($owner && $owner->wedding_regime === $value) ? 'selected' : '' }}>
                                        {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-3">
                        <label for="owners[{{ $key }}][rg]">RG</label>
                        <input id="owners[{{ $key }}][rg]" name="owners[{{ $key }}][rg]" type="text" class="form-control" value="{{ !$owner ? '' : $owner->rg }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="owners[{{ $key }}][rg_expedition]">Data de Expedição</label>
                        <input id="owners[{{ $key }}][rg_expedition]" name="owners[{{ $key }}][rg_expedition]" type="date" class="form-control dataBr" value="{{ ($owner && $owner->rg_expedition) ? $owner->rg_expedition->toDateString() : '' }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="owners[{{ $key }}][cpf]">CPF</label>
                        <input id="owners[{{ $key }}][cpf]" name="owners[{{ $key }}][cpf]" type="text" class="form-control cpf" data-masked="000.000.000-00" data-masked-reverse value="{{ !$owner ? '' : $owner->cpf }}">
                    </div>

                    <div class="form-group col-xs-12 col-md-3">
                        <label for="owners[{{ $key }}][date_of_birth]">Data de Nacimento</label>
                        <input id="owners[{{ $key }}][date_of_birth]" name="owners[{{ $key }}][date_of_birth]" type="date" class="form-control dataBr" value="{{ ($owner && $owner->date_of_birth) ? $owner->date_of_birth->toDateString() : '' }}">
                    </div>
                </div>

                @component('dashboard.shared.box.steps-form.partials.address', [
                    'step' => $step,
                    'address' => !$owner ? '' : $owner->address,
                    'type' => 'owners',
                    'parentId' => $key,
                ])
                @endcomponent
        </div>
    </div>
</div>
