@if ($process->showViability())
    <h3>{{ $step['label'] }}</h3>
    <section>
        @php
            $viability = $process->viability
        @endphp

        <input type="hidden" name="viability[id]" value="{{ optional($viability)->id }}">
        <div class="row">
            <div class="form-group col-xs-12 col-md-3">
                <label for="viability[property_type]">Tipo do imóvel</label>
                <select id="viability[property_type]" name="viability[property_type]" class="form-control">
                    @foreach(Gcr\Viability::attributeOptions('property_type') as $value => $label)
                        <option value="{{ $value }}" @if(optional($viability)->property_type === $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-3">
                    <label for="viability[registration_number]">Numero de cadastro</label>
                <input id="viability[registration_number]" name="viability[registration_number]" type="text" class="form-control" value="{{ optional($viability)->registration_number }}">
            </div>

            <div class="form-group col-xs-12 col-md-3">
                <label for="viability[property_area]">Área do imóvel</label>
                <input id="viability[property_area]" name="viability[property_area]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse value="{{ optional($viability)->property_area }}">
            </div>

            <div class="form-group col-xs-12 col-md-3">
                <label for="viability[establishment_area]">Área do estabelecimento</label>
                <input id="viability[establishment_area]" name="viability[establishment_area]" type="text" class="form-control" data-masked="#.##0,00" data-masked-reverse value="{{ optional($viability)->establishment_area }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-xs-12 col-md-12">
                <label for="viability[establishment_has_avcb_clcb]">Local do estabelecimento possui AVCB/CLCB.</label>
                <select id="viability[establishment_has_avcb_clcb]" name="viability[establishment_has_avcb_clcb]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->establishment_has_avcb_clcb === $value)
                                selected
                            @endif>
                                @if(!$value)
                                    Não
                                @else
                                    Sim
                                @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-6 hidden">
                <label for="viability[avcb_clcb_number]">Número AVCB/CLCB</label>
                <input id="viability[avcb_clcb_number]" name="viability[avcb_clcb_number]" type="text" class="form-control"  value="{{ optional($viability)->avcb_clcb_number }}">
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[same_as_business_address]">A atividade é exercida no mesmo local do endereço da empresa.</label>
                <select id="viability[same_as_business_address]" name="viability[same_as_business_address]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->same_as_business_address === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[thirst]">Administração central da empresa, presidencia, diretoria.</label>
                <select id="viability[thirst]" name="viability[thirst]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->thirst === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[administrative_office]">Estabelecimento onde são exercidas atividades meramente administratives.</label>
                <select id="viability[administrative_office]" name="viability[administrative_office]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->administrative_office === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[closed_deposit]">Estabelecimento onde a empresa armazena mercadorias próprias destinadas à industrialização e/ou comercialização, no qual não se realizam vendas.</label>
                <select id="viability[closed_deposit]" name="viability[closed_deposit]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->closed_deposit === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[warehouse]">Estabelecimento onde a empresa armazena artigos de consumo para uso próprio.</label>
                <select id="viability[warehouse]" name="viability[warehouse]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->warehouse === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[repair_workshop]">Estabelecimento onde se efetua manutenção e reparação exclusivamente de bens do ativo fixo da própria empresa.</label>
                <select id="viability[repair_workshop]" name="viability[repair_workshop]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->repair_workshop === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[garage]">Para estabelecimento de veiculos próprios, uso exclusivo da empresa.</label>
                <select id="viability[garage]" name="viability[garage]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->garage === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[fuel_supply_unit]">Estabelecimento de abastecimento de combustiveis para uso pela frota própria.</label>
                <select id="viability[fuel_supply_unit]" name="viability[fuel_supply_unit]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->fuel_supply_unit === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[exposure_point]">Estabelecimento para exposição e demonstração de produtos próprios, sem realização de transações comerciais, tipo showroom.</label>
                <select id="viability[exposure_point]" name="viability[exposure_point]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->exposure_point === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[training_center]">Estabelecimento destinado a treinamento, de uso exclusivo da empresa, para realização de atividades de capacitação e treinamentos de recursos humanos.</label>
                <select id="viability[training_center]" name="viability[training_center]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->training_center === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12">
                <label for="viability[data_processing_center]">Estabelecimento de processo de dados, de uso exclusivo da empresa, para realização de atividades na área de informática em geral.</label>
                <select id="viability[data_processing_center]" name="viability[data_processing_center]">
                    @foreach(range(0, 1) as $value)
                        <option
                            value="{{ $value }}"
                            @if(optional($viability)->data_processing_center === $value)
                            selected
                            @endif>
                            @if(!$value)
                                Não
                            @else
                                Sim
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>
@endif
