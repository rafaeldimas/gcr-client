<form action="{{ route('dashboard.process.index') }}">
    <input type="hidden" name="type_company" value="{{ request('type_company') }}">

    <div class="form-group col-xs-12 col-md-3">
        <label for="search[protocol]">Protocolo</label>
        <input id="search[protocol]" name="search[protocol]" class="form-control" value="{{ request('search.protocol') }}" />
    </div>

    @can('admin')
        <div class="form-group col-xs-12 col-md-3">
            <label for="search[user_id]">Usuário</label>
            <select id="search[user_id]" name="search[user_id]" class="form-control">
                <option disabled selected>Selecione um Usuário</option>
                @foreach(Gcr\User::all() as $user)
                    <option value="{{ $user->id }}" @if($user->id === (int) request('search.user_id')) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endcan

    <div class="form-group col-xs-12 col-md-3">
        <label for="search[company_name]">Nome Empresarial</label>
        <input id="search[company_name]" name="search[company_name]" class="form-control" value="{{ request('search.company_name') }}" />
    </div>

    <div class="form-group col-xs-12 col-md-3">
        <label for="search[company_cnpj]">CNPJ Empresarial</label>
        <input id="search[company_cnpj]" name="search[company_cnpj]" type="text" class="form-control cnpj" data-masked="00.000.000/0000-00" value="{{ request('search.company_cnpj') }}">
    </div>

    <div class="form-group col-xs-12">
        <button class="btn btn-primary">Pesquisar</button>
    </div>
</form>
