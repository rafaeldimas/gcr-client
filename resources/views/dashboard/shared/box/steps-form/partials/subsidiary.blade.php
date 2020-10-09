@php
    /** @var Gcr\Subsidiary $subsidiary */
    $subsidiary = optional($subsidiary);
@endphp
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="tab-subsidiary-{{ $key }}">
        <h4 class="panel-title" style="display: flex; justify-content: space-between; align-items: baseline">
            <a role="button" data-toggle="collapse" data-parent="#subsidiaries" href="#tab-content-subsidiary-{{ $key }}" aria-expanded="false" aria-controls="tab-content-subsidiary-{{ $key }}">
                {{ $step['label'] }} #{{ $key }}
            </a>
            <button type="button" class="btn btn-danger" data-button-remove-subsidiary="{{ $subsidiary->id }}">
                <i class="fa fa-close"></i>
            </button>
        </h4>
    </div>
    <div id="tab-content-subsidiary-{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tab-subsidiary-{{ $key }}">
        <div class="panel-body">
            <input type="hidden" name="subsidiaries[{{ $key }}][id]" value="{{ $subsidiary->id }}">

            <div class="row">
                <div class="form-group col-xs-12 col-md-6">
                    <label for="subsidiaries[{{ $key }}][request]">Tipo de solicitação</label>
                    <select id="subsidiaries[{{ $key }}][request]" name="subsidiaries[{{ $key }}][request]" class="form-control" data-s2 required>
                        <option value="" selected disabled>Selecione...</option>
                        @foreach(Gcr\Subsidiary::attributeOptions('request') as $value => $label)
                            <option
                                value="{{ $value }}"
                                {{ $value === $subsidiary->request ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <span id="subsidiaries[{{ $key }}][request]" class="help-block text-bold text-red">Questionário de Viabilidade obrigatório para os atos de abertura, alteração de endereço e/ou atividade.</span>
                </div>

                <div class="form-group col-xs-12 col-md-6 @if(!$subsidiary->request !== \Gcr\Subsidiary::REQUEST_CHANGING) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][fields_changed][]">Tipo de Alteração</label>
                    <select id="subsidiaries[{{ $key }}][fields_changed][]" name="subsidiaries[{{ $key }}][fields_changed][]" class="form-control" data-s2 multiple @if(!$subsidiary->request !== \Gcr\Subsidiary::REQUEST_CHANGING) disabled @endif required>
                        <option value="" disabled>Selecione...</option>
                        @foreach(Gcr\Subsidiary::attributeOptions('fields_changed') as $value => $label)
                            <option
                                value="{{ $value }}"
                                {{ in_array($value, optional($subsidiary)->fields_changed ?: []) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-md-4 @if(!$subsidiary->nire) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][nire]">NIRE @if ($process->isEditingSubsidiary()) (da filial) @endif</label>
                    <input id="subsidiaries[{{ $key }}][nire]" name="subsidiaries[{{ $key }}][nire]" type="text" class="form-control" @if(!$subsidiary->nire) disabled @endif required value="{{ $subsidiary->nire }}" maxlength="11">
                </div>

                <div class="form-group col-xs-12 col-md-4 @if(!$subsidiary->cnpj) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][cnpj]">CNPJ @if ($process->isEditingSubsidiary()) (da filial) @endif</label>
                    <input id="subsidiaries[{{ $key }}][cnpj]" name="subsidiaries[{{ $key }}][cnpj]" type="text" class="form-control cnpj" data-masked="00.000.000/0000-00" data-masked-reverse @if(!$subsidiary->cnpj) disabled @endif required value="{{ $subsidiary->cnpj }}">
                </div>

                <div class="form-group col-xs-12 col-md-4 @if(!$subsidiary->share_capital) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][share_capital]">Capital Social @if($process->request === \Gcr\Subsidiary::REQUEST_OPENING) (Não havendo destaque de capital, favor preencher 0,00) @endif</label>
                    <input id="subsidiaries[{{ $key }}][share_capital]" name="subsidiaries[{{ $key }}][share_capital]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse @if(!$subsidiary->share_capital) disabled @endif required value="{{ $subsidiary->share_capital }}">
                    @if ($process->isEireli())
                        <span id="subsidiaries[{{ $key }}][share_capital]" class="help-block">A partir de 100 vezes o salário minimo</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-12 @if(!$subsidiary->activity_description) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][activity_description]">Descrição da Atividade</label>
                    <textarea id="subsidiaries[{{ $key }}][activity_description]" name="subsidiaries[{{ $key }}][activity_description]" class="form-control" @if(!$subsidiary->activity_description) disabled @endif required>{{ $subsidiary->activity_description }}</textarea>
                </div>
            </div>

            <div class="subsidiary-cnaes @if(!$subsidiary->cnaes) hidden @endif">
                @component('dashboard.shared.box.steps-form.partials.cnae', [
                    'step' => $step,
                    'cnaes' => optional($subsidiary)->cnaes,
                    'type' => 'subsidiaries',
                    'parentId' => $key,
                ])
                @endcomponent
            </div>

            <div class="subsidiary-address @if(!$subsidiary->address) hidden @endif">
                @component('dashboard.shared.box.steps-form.partials.address', [
                    'step' => $step,
                    'address' => optional($subsidiary)->address,
                    'type' => 'subsidiaries',
                    'parentId' => $key,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>
