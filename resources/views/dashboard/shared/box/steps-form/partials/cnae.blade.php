<div class="row box-cnaes">
    <h2 class="text-center">Cnaes</h2>

    <template id="new-cnae">
        <div class="form-group col-xs-12 col-md-3">
            <input type="hidden" name="company[cnaes][][id]">
            <label for="company[cnaes][][number]">Cnae</label>
            <input id="company[cnaes][][number]" name="company[cnaes][][number]" type="text" class="form-control" data-masked="0000-0/00" />
        </div>
    </template>

    <div id="cnaes" data-last-id="{{ !$cnaes ? '0' : $cnaes->count() }}">
        @if ($cnaes && $cnaes->count())
            @foreach ($cnaes as $key => $cnae)
                @php
                    $key++
                @endphp

                <div class="form-group col-xs-12 col-md-3">
                    <input type="hidden" name="company[cnaes][{{ $key }}][id]" value="{{ !$cnae ? '' : $cnae->id }}">
                    <label for="company[cnaes][{{ $key }}][number]">Cnae</label>
                    <input id="company[cnaes][{{ $key }}][number]" name="company[cnaes][{{ $key }}][number]" type="text" class="form-control" data-masked="0000-0/00" value="{{ !$cnae ? '' : $cnae->number }}" />
                </div>
            @endforeach
        @else
            <div class="form-group col-xs-12 col-md-3">
                <input type="hidden" name="company[cnaes][][id]">
                <label for="company[cnaes][][number]">Cnae</label>
                <input id="company[cnaes][][number]" name="company[cnaes][][number]" type="text" class="form-control" data-masked="0000-0/00" />
            </div>
        @endif
    </div>

    <div class="box-add-new-cnae">
        <button type="button" class="btn btn-lg btn-primary" title="Adicionar novo cnae" data-button-add-new-cnae>
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>
