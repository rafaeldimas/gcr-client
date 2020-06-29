@php
    $prefixName = in_array($type, [ 'subsidiaries' ]) ? "{$type}[{$parentId}][cnaes]" : "{$type}[cnaes]"
@endphp
<div class="row box-cnaes">
    <h2 class="text-center">Cnaes <small class="text-danger">(Informá-los na ordem correta - Cnae Principal e Secundários)</small></h2>

    <template id="new-cnae">
        <div class="form-group col-xs-12 col-md-3">
            <input type="hidden" name="{{ $prefixName }}[][id]">
            <label for="{{ $prefixName }}[][number]">Cnae</label>
            <input id="{{ $prefixName }}[][number]" name="{{ $prefixName }}[][number]" type="text" class="form-control" data-masked="0000-0/00" />
        </div>
    </template>

    <div id="cnaes" data-last-id="{{ !$cnaes ? '0' : $cnaes->count() }}">
        @if ($cnaes && $cnaes->count())
            @foreach ($cnaes as $key => $cnae)
                @php
                    $key++
                @endphp

                <div class="form-group col-xs-12 col-md-3">
                    <input type="hidden" name="{{ $prefixName }}[{{ $key }}][id]" value="{{ !$cnae ? '' : $cnae->id }}">
                    <label for="{{ $prefixName }}[{{ $key }}][number]">Cnae</label>
                    <input id="{{ $prefixName }}[{{ $key }}][number]" name="{{ $prefixName }}[{{ $key }}][number]" type="text" class="form-control" data-masked="0000-0/00" value="{{ !$cnae ? '' : $cnae->number }}" />
                </div>
            @endforeach
        @else
            <div class="form-group col-xs-12 col-md-3">
                <input type="hidden" name="{{ $prefixName }}[][id]">
                <label for="{{ $prefixName }}[][number]">Cnae</label>
                <input id="{{ $prefixName }}[][number]" name="{{ $prefixName }}[][number]" type="text" class="form-control" data-masked="0000-0/00" />
            </div>
        @endif
    </div>

    <div class="box-add-new-cnae">
        <button type="button" class="btn btn-lg btn-primary" title="Adicionar novo cnae" data-button-add-new-cnae>
            <i class="fa fa-plus"></i>
            Adicionar Cnae(s) Secundário(s)
        </button>
    </div>
</div>
