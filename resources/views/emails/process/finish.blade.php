<?php
/** @var Gcr\Process $process */
/** @var Gcr\Owner $owner */
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        /* http://meyerweb.com/eric/tools/css/reset/
           v2.0 | 20110126
           License: none (public domain)
        */

        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            background: #f5f5f5;
        }

        .container {
            margin: 0 auto;
            padding: 15px;
            width: 100%;
            max-width: 800px;
            background: #fff;
        }

        .header,
        .footer {
            text-align: center;
            background: #010c23;
        }

        .header .header-logo {
            margin-bottom: 15px;
        }

        .header h1 {
            padding-bottom: 15px;
            color: #fff;
            font-size: 26px;
            font-weight: 700;
        }

        .body {
            padding: 15px 0;
            margin: 15px auto;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .body .steps .step-card {
            margin: 15px auto;
        }

        .body .steps .step-card .step-header {
            padding: 15px;
            color: #9b7e2b;
            background: #010c23;
            border-radius: 5px;
        }

        .body .steps .step-card .step-body {
            padding: 15px;
        }

        .body .steps .step-card .step-body .fields .field {
            margin-bottom: 5px;
        }

        .body .steps .step-card .step-body .fields .field .label {
            color: #010c23;
            font-weight: 700;
        }

        .body .steps .step-card.user .step-body {
            display: inline-block;
            width: 100%;
        }

        .body .steps .step-card.user .step-body > div {
            float: left;
            width: 45%;
        }

        .body .steps .step-card.user .step-body > div:first-of-type {
            margin-right: 5%;
        }

        .body .steps .step-card.user .step-body .user-logo {
            height: 105px;
        }

        .body .steps .step-card.owners .step-body .owner,
        .body .steps .step-card.company .step-body {
            margin: 15px auto;
            padding: 15px;
            display: inline-block;
            width: 100%;
            border: 1px solid #010c23;
            border-radius: 5px;
        }

        .body .steps .step-card.owners .step-body .owner > div,
        .body .steps .step-card.company .step-body > div {
            float: left;
            width: 45%;
        }

        .body .steps .step-card.owners .step-body .owner > div:first-of-type,
        .body .steps .step-card.company .step-body > div:first-of-type {
            margin-right: 5%;
        }

        .body .steps .step-card.owners .step-body .owner h2,
        .body .steps .step-card.company .step-body h2 {
            margin-bottom: 15px;
            padding-bottom: 5px;
            color: #010c23;
            font-weight: 700;
            border-bottom: 1px solid #010c23;
        }

        .body .steps .step-card.viability .step-body .fields .field {
            padding: 15px 0;
            border-bottom: 1px solid #010c23;
        }

        .body .steps .step-card.viability .step-body .fields .field span {
            display: block;
        }

        .body .steps .step-card.viability .step-body .fields .field span:last-of-type {
            margin-top: 10px;
        }

        .footer {
            padding: 15px 0;
            background: #010c23;
        }

        .footer .link-site {
            color: #fff;
            font-size: 26px;
            font-weight: 700;
            text-decoration: unset;
        }

        .footer .link-site .footer-logo {
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img class="header-logo" src="{{ asset('storage/site-logo.png') }}" alt="logo">
        <h1>GCR Legalização</h1>
    </div>
    <div class="body">
        <div class="steps">
            <div class="step-card general">
                <div class="step-header">
                    <h1 class="title">Dados Gerais</h1>
                </div>
                <div class="step-body">
                    <div class="fields">
                        <div class="field">
                            <span class="label">Operação: </span>
                            <span class="value">{{ $process->operationCode() }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Tipo de empresa: </span>
                            <span class="value">{{ $process->typeCompanyCode() }}</span>
                        </div>
                        @if($process->isUpdating())
                        <div class="field">
                            <span class="label">Campos que serão alterados: </span>
                            <span class="value">{{ implode(', ', $process->fieldsEditingCode()) }}</span>
                        </div>
                        @endif
                        @if($process->isTransformation())
                        <div class="field">
                            <span class="label">Novo tipo de empresa: </span>
                            <span class="value">{{ $process->newTypeCompanyCode() }}</span>
                        </div>
                        @endif
                        <div class="field">
                            <span class="label">Deseja receber o seu documento digitalizado? </span>
                            <span class="value">{{ $process->scanned_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Deseja receber o seu documento por correio? </span>
                            <span class="value">{{ $process->post_office_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Breve Descrição: </span>
                            <span class="value">{{ $process->description }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step-card user">
                <div class="step-header">
                    <h1 class="title">Usúario</h1>
                </div>
                <div class="step-body">
                    <div class="fields">
                        <div class="field">
                            <span class="label">Tipo de Usúario: </span>
                            <span class="value">{{ $process->user->typeLabel }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Nome: </span>
                            <span class="value">{{ $process->user->name }}</span>
                        </div>
                        <div class="field">
                            <span class="label">E-mail: </span>
                            <span class="value">{{ $process->user->email }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Telefone: </span>
                            <span class="value">{{ $process->user->phone }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Celular: </span>
                            <span class="value">{{ $process->user->mobile_phone }}</span>
                        </div>
                    </div>
                    @if($process->user->logo)
                    <div class="user-logo">
                        <img src="{{ $process->user->logoUrl() }}" alt="Logo do Usúario"/>
                    </div>
                    @endif
                </div>
            </div>
            <div class="step-card owners">
                <div class="step-header">
                    <h1 class="title">Empresários | Sócios | Integrantes</h1>
                </div>
                <div class="step-body">
                    @foreach($process->owners as $owner)
                        <div class="owner">
                            <div class="data">
                                <h2>Dados da pessoa</h2>
                                <div class="fields">
                                    <div class="field">
                                        <span class="label">Cargo: </span>
                                        <span class="value">{{ $owner->jobRolesCode() }}</span>
                                    </div>
                                    @if($owner->showJobRolesOther())
                                    <div class="field">
                                        <span class="label">Este cargo não estava presente na lista: </span>
                                        <span class="value">{{ $owner->job_roles_other }}</span>
                                    </div>
                                    @endif
                                    <div class="field">
                                        <span class="label">Nome Completo: </span>
                                        <span class="value">{{ $owner->name }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Estado Civil: </span>
                                        <span class="value">{{ $owner->maritalStatusCode() }}</span>
                                    </div>
                                    @if($owner->showWeddingWegime())
                                    <div class="field">
                                        <span class="label">Regime de Casamento: </span>
                                        <span class="value">{{ $owner->weddingRegimeCode() }}</span>
                                    </div>
                                    @endif
                                    <div class="field">
                                        <span class="label">RG: </span>
                                        <span class="value">{{ $owner->rg }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Data de Expedição: </span>
                                        <span class="value">{{ $owner->rg_expedition->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">CPF: </span>
                                        <span class="value">{{ $owner->cpf }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Data de Nacimento: </span>
                                        <span class="value">{{ $owner->date_of_birth->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="address">
                                <h2>Dados do endereço</h2>
                                <div class="fields">
                                    <div class="field">
                                        <span class="label">CEP: </span>
                                        <span class="value">{{ $owner->address->postcode }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Logradouro: </span>
                                        <span class="value">{{ $owner->address->street }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Número: </span>
                                        <span class="value">{{ $owner->address->number }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Bairro: </span>
                                        <span class="value">{{ $owner->address->district }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Cidade: </span>
                                        <span class="value">{{ $owner->address->city }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Estado: </span>
                                        <span class="value">{{ $owner->address->state }}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">País: </span>
                                        <span class="value">{{ $owner->address->country }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="step-card company">
                <div class="step-header">
                    <h1 class="title">Empresa</h1>
                </div>
                <div class="step-body">
                    <div class="data">
                        <h2>Dados da pessoa</h2>
                        <div class="fields">
                            <div class="field">
                                <span class="label">Nome: </span>
                                <span class="value">{{ $process->company->name }}</span>
                            </div>
                            <div class="field">
                                <span class="label">NIRE: </span>
                                <span class="value">{{ $process->company->nire }}</span>
                            </div>
                            <div class="field">
                                <span class="label">CNPJ: </span>
                                <span class="value">{{ $process->company->cnpj }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Capital Social: </span>
                                <span class="value">{{ $process->company->share_capital }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Descrição da Atividade: </span>
                                <span class="value">{{ $process->company->activity_description }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Porte da Empresa: </span>
                                <span class="value">{{ $process->company->sizeCode() }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Data de Assinatura: </span>
                                <span class="value">{{ $process->company->signed->format('d/m/Y') }}</span>
                            </div>
                            <div class="field">
                                <span class="label">CNAES: </span>
                                <span class="value">{{ $process->company->cnaesStringFormated() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="address">
                        <h2>Dados do endereço</h2>
                        <div class="fields">
                            <div class="field">
                                <span class="label">CEP: </span>
                                <span class="value">{{ $process->company->address->postcode }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Logradouro: </span>
                                <span class="value">{{ $process->company->address->street }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Número: </span>
                                <span class="value">{{ $process->company->address->number }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Bairro: </span>
                                <span class="value">{{ $process->company->address->district }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Cidade: </span>
                                <span class="value">{{ $process->company->address->city }}</span>
                            </div>
                            <div class="field">
                                <span class="label">Estado: </span>
                                <span class="value">{{ $process->company->address->state }}</span>
                            </div>
                            <div class="field">
                                <span class="label">País: </span>
                                <span class="value">{{ $process->company->address->country }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step-card viability">
                <div class="step-header">
                    <h1 class="title">Questionário de Viabilidade</h1>
                </div>
                <div class="step-body">
                    <div class="fields">
                        <div class="field">
                            <span class="label">Tipo do imóvel: </span>
                            <span class="value">{{ $process->viability->propertyTypeCode() }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Numero de cadastro: </span>
                            <span class="value">{{ $process->viability->registration_number }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Área do imóvel: </span>
                            <span class="value">{{ $process->viability->property_area }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Área do estabelecimento: </span>
                            <span class="value">{{ $process->viability->establishment_area }}</span>
                        </div>
                        <div class="field">
                            <span class="label">A atividade é exercida no mesmo local do endereço da empresa: </span>
                            <span class="value">{{ $process->viability->same_as_business_address_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Administração central da empresa, presidencia, diretoria: </span>
                            <span class="value">{{ $process->viability->thirst_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento onde são exercidas atividades meramente administratives: </span>
                            <span class="value">{{ $process->viability->administrative_office_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento onde a empresa armazena mercadorias próprias destinadas à industrialização e/ou comercialização, no qual não se realizam vendas: </span>
                            <span class="value">{{ $process->viability->closed_deposit_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento onde a empresa armazena artigos de consumo para uso próprio: </span>
                            <span class="value">{{ $process->viability->warehouse_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento onde se efetua manutenção e reparação exclusivamente de bens do ativo fixo da própria empresa: </span>
                            <span class="value">{{ $process->viability->repair_workshop_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Para estabelecimento de veiculos próprios, uso exclusivo da empresa: </span>
                            <span class="value">{{ $process->viability->garage_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento de abastecimento de combustiveis para uso pela frota própria: </span>
                            <span class="value">{{ $process->viability->fuel_supply_unit_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento para exposição e demonstração de produtos próprios, sem realização de transações comerciais, tipo showroom: </span>
                            <span class="value">{{ $process->viability->exposure_point_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento destinado a treinamento, de uso exclusivo da empresa, para realização de atividades de capacitação e treinamentos de recursos humanos: </span>
                            <span class="value">{{ $process->viability->training_center_human }}</span>
                        </div>
                        <div class="field">
                            <span class="label">Estabelecimento de processo de dados, de uso exclusivo da empresa, para realização de atividades na área de informática em geral: </span>
                            <span class="value">{{ $process->viability->data_processing_center_human }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <a href="{{route('login')}}" class="link-site">
            <img class="footer-logo" src="{{ asset('storage/site-logo.png') }}" alt="logo">
            <span>GCR Legalização - Area Restrita</span>
        </a>
    </div>
</div>
</body>
</html>
