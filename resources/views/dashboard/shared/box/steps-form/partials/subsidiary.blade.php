@php
    /** @var Gcr\Subsidiary $subsidiary */
    $subsidiary = optional($subsidiary);
@endphp
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="tab-subsidiary-{{ $key }}">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#subsidiaries" href="#tab-content-subsidiary-{{ $key }}" aria-expanded="false" aria-controls="tab-content-subsidiary-{{ $key }}">
                {{ $step['label'] }} #{{ $key }}
            </a>
        </h4>
    </div>
    <div id="tab-content-subsidiary-{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tab-subsidiary-{{ $key }}">
        <div class="panel-body">
            <input type="hidden" name="subsidiaries[{{ $key }}][id]" value="{{ $subsidiary->id }}">

            <div class="row">
                <div class="form-group col-xs-12 col-md-3">
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
                </div>

                <div class="form-group col-xs-12 col-md-3 @if(!$subsidiary->nire) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][nire]">NIRE</label>
                    <input id="subsidiaries[{{ $key }}][nire]" name="subsidiaries[{{ $key }}][nire]" type="text" class="form-control" @if(!$subsidiary->nire) disabled @endif value="{{ $subsidiary->nire }}" maxlength="11">
                </div>

                <div class="form-group col-xs-12 col-md-3 @if(!$subsidiary->cnpj) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][cnpj]">CNPJ</label>
                    <input id="subsidiaries[{{ $key }}][cnpj]" name="subsidiaries[{{ $key }}][cnpj]" type="text" class="form-control cnpj" data-masked="00.000.000/0000-00" data-masked-reverse @if(!$subsidiary->cnpj) disabled @endif value="{{ $subsidiary->cnpj }}">
                </div>

                <div class="form-group col-xs-12 col-md-3 @if(!$subsidiary->share_capital) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][share_capital]">Capital Social</label>
                    <input id="subsidiaries[{{ $key }}][share_capital]" name="subsidiaries[{{ $key }}][share_capital]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse @if(!$subsidiary->share_capital) disabled @endif value="{{ $subsidiary->share_capital }}">
                    @if ($process->isEireli())
                        <span id="subsidiaries[{{ $key }}][share_capital]" class="help-block">A partir de 100 vezes o salário minimo</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-12 @if(!$subsidiary->activity_description) hidden @endif">
                    <label for="subsidiaries[{{ $key }}][activity_description]">Descrição da Atividade</label>
                    <textarea id="subsidiaries[{{ $key }}][activity_description]" name="subsidiaries[{{ $key }}][activity_description]" class="form-control" @if(!$subsidiary->activity_description) disabled @endif>{{ $subsidiary->activity_description }}</textarea>
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
