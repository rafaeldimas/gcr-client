/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * @todo Refectory to one responsibilities
 */

window.jQuery(function ($) {
    jQuery.validator.addClassRules({
        cnpj: { cnpjBR: true },
        cpf: { cpfBR: true },
        cep: { postalcodeBR: true },
        dataBr: { dateISO: true }
    });

    const $forms = $('[data-form-process]');
    if ($forms.length) {
        $forms.each(function (index, form) {
            const submitForm = (type) => {
                const $form = $(form);
                const formData = new FormData(form);

                if (type === 'finished') {
                    formData.append('process[finished]', 1);
                }

                window.axios.post($form.attr('action'), formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(response => {
                    if (response && !response.data.error) {
                        const owners = response.data.owners;
                        const company = response.data.company;
                        const viability = response.data.viability;
                        const documents = response.data.documents;
                        const validationErrors = response.data.validationErrors;
                        const url = response.data.url;

                        $.map(owners, (owner, key) => {
                            const $owner = $(`input[type="hidden"][name="owners[${key}][id]"]`);
                            const $ownerAddress = $(`input[type="hidden"][name="owners[${key}][address][id]"]`);

                            $owner.val(owner.id);
                            $ownerAddress.val(owner.address_id);
                        });

                        if (company) {
                            const $company = $('input[type="hidden"][name="company[id]"]');
                            const $companyAddress = $('input[type="hidden"][name="company[address][id]"]');

                            $company.val(company.id);
                            $companyAddress.val(company.address_id);

                            $.map(company.cnaes, (cnae, key) => {
                                const $cnae = $(`input[type="hidden"][name="company[cnaes][${key}][id]"]`);
                                $cnae.val(cnae.id);
                            });
                        }

                        if (viability) {
                            const $viability = $('input[type="hidden"][name="viability[id]"]');
                            $viability.val(viability.id);
                        }

                        $.map(documents, (document, type) => {
                            const $document = $(`input[type="hidden"][name="documents[${type}][id]"]`);
                            $document.val(document.id);
                        });

                        if (url) {
                            const $template = $('#alert-template');

                            const $alert = $template.clone().find('.alert');
                            $alert.toggleClass('alert-success');

                            $alert.find('ul').html('' +
                                '<li>' +
                                    'Todos os dados informados foram salvos, e o processo foi finalizado, aguarde até ser redirecionado para a listagem dos processos.' +
                                '</li>'
                            );
                            $('.box-body').prepend($alert);

                            setTimeout(() => {
                                window.location.replace(url);
                            }, 3000);
                        } else {
                            if (type === 'finished') {
                                const $template = $('#alert-template');

                                const $alert = $template.clone().find('.alert');
                                $alert.toggleClass('alert-warning');

                                let $message = '';
                                $message += '<li>Todos os dados informados foram salvos, para finalizar, preencha todas as informações solicitadas.</li>';
                                $.map(validationErrors, errors => {
                                    $.map(errors, error => $message += '<li>'+error+'</li>');
                                });
                                $alert.find('ul').html($message);
                                $('.box-body').prepend($alert);
                            }
                        }
                    }
                }).catch(error => {
                    const $template = $('#alert-template');

                    const $alert = $template.clone().find('.alert');
                    $alert.toggleClass('alert-danger');
                    $alert.find('ul').html('<li>Ocorreu um erro, recarregue a pagina e tente novamente. Caso persista entre em contato conosco.</li>');
                    $('.box-body').prepend($alert);
                });
            };

            $(form).steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                enableAllSteps: true,
                autoFocus: true,
                labels: {
                    cancel: "Cancelar",
                    current: "Etapa Atual:",
                    pagination: "Paginação",
                    finish: "Finalizar",
                    next: "Proximo",
                    previous: "Anterior",
                    loading: "Carregando ..."
                },
                onStepChanging: (event, currentIndex, newIndex) => {
                    if (currentIndex > newIndex) {
                        return true;
                    }

                    if (currentIndex < newIndex) {
                        $(form).find(".body:eq(" + newIndex + ") label.error").remove();
                        $(form).find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }

                    $(form).validate().settings.ignore = ":disabled,:hidden";
                    return $(form).valid();
                },
                onStepChanged: (event, currentIndex, newIndex) => {
                    if (currentIndex > newIndex) {
                        submitForm('changed');
                    }
                },
                onFinished: () => {
                    submitForm('finished');
                }
            }).validate();
        });
    }

    const $buttonAddNewOwner = $('[data-button-add-new-owner]');
    if ($buttonAddNewOwner.length) {
        $buttonAddNewOwner.on('click', function (e) {
            e.preventDefault();

            const $newOwnerTemplate = $('#new-owner');
            const $ownersContainer = $('#owners');
            const $contents = $newOwnerTemplate.contents().clone(true, true);
            const lastId = $ownersContainer.attr('data-last-id');
            const newId = parseInt(lastId, 10) + 1;

            $contents.find('#tab-owner-').attr('id', `#tab-owner-${newId}`);

            $contents.find('a[data-toggle="collapse"]').attr({
                'aria-controls': `tab-content-owner-${newId}`,
                'href': `#tab-content-owner-${newId}`
            }).text($contents.find('a[data-toggle="collapse"]').text().trim() + newId)
            .on('click', function(e) {
                e.preventDefault();
                const $current = $(this);
                const $panel = $current.closest('.panel');
                const $content = $panel.find('.panel-collapse');

                $current.attr('aria-expanded', !$current.attr('aria-expanded'));
                $content.toggleClass('in');
            });

            $contents.find('#tab-content-owner-').find('input, select').each((index, element) => {
                const $current = $(element);
                const oldStr = $current.attr('name');
                const newStr = oldStr.replace('owners[]', `owners[${newId}]`);
                $current.attr({
                    'id': newStr,
                    'name': newStr
                });
                $current.siblings('label').attr('for', newStr);
            });

            $contents.find('#tab-content-owner-').attr({
                'id': `#tab-content-owner-${newId}`,
                'aria-labelledby': `tab-owner-${newId}`
            });

            $ownersContainer.append($contents);
            $ownersContainer.attr('data-last-id', newId);
            init();

            const $lastPanel = $ownersContainer
                .find('.panel:last');

            $lastPanel
                .find('a[data-toggle="collapse"]')
                .click();

            $lastPanel
                .find('select:first, .panel:last input:first:not([type="hidden"])')
                .first()
                .focus();
        });
    }

    const $buttonAddNewCnae = $('[data-button-add-new-cnae]');
    if ($buttonAddNewCnae.length) {
        $buttonAddNewCnae.on('click', function (e) {
            e.preventDefault();

            const $newCnaeTemplate = $('#new-cnae');
            const $cnaesContainer = $('#cnaes');
            const $contents = $newCnaeTemplate.contents().clone(true, true);
            const lastId = $cnaesContainer.attr('data-last-id');
            const newId = parseInt(lastId, 10) + 1;

            $contents.find('input').each((index, element) => {
                const $current = $(element);
                const oldStr = $current.attr('name');
                const newStr = oldStr.replace('company[cnaes][]', `company[cnaes][${newId}]`);
                $current.attr({
                    'id': newStr,
                    'name': newStr
                });
                $current.siblings('label').attr('for', newStr);
            });

            $cnaesContainer.append($contents);
            $cnaesContainer.attr('data-last-id', newId);
            init();
        });
    }

    (function initEvents () {
        $(document).on('change', 'select[name*="marital_status"]', function (e) {
            e.preventDefault();

            if ($(this).val() === '2') {
                $(this).closest('.form-group').removeClass('col-md-6').addClass('col-md-3');
                $(this).closest('.row').find('select[name*="wedding_regime"]').attr('disabled', false);
                $(this).closest('.row').find('select[name*="wedding_regime"]').closest('.form-group').removeClass('hidden');
            } else {
                $(this).closest('.form-group') .removeClass('col-md-3').addClass('col-md-6');
                $(this).closest('.row').find('select[name*="wedding_regime"]').attr('disabled', true);
                $(this).closest('.row').find('select[name*="wedding_regime"]').closest('.form-group').addClass('hidden');
            }
        });

        $(document).on('change', 'select[name*="job_role"]', function (e) {
            e.preventDefault();

            if ($.inArray('4', $(this).val()) !== -1) {
                $(this).closest('.form-group').removeClass('col-md-12').addClass('col-md-6');
                $(this).closest('.row').find('input[name*="job_roles_other"]').attr('disabled', false);
                $(this).closest('.row').find('input[name*="job_roles_other"]').closest('.form-group').removeClass('hidden');
            } else {
                $(this).closest('.form-group') .removeClass('col-md-6').addClass('col-md-12');
                $(this).closest('.row').find('input[name*="job_roles_other"]').attr('disabled', true);
                $(this).closest('.row').find('input[name*="job_roles_other"]').closest('.form-group').addClass('hidden');
            }
        });

        $(document).on('change', 'select[name*="establishment_has_avcb_clcb"]', function (e) {
            e.preventDefault();

            if ($(this).val() === '1') {
                $(this).closest('.form-group').removeClass('col-md-12').addClass('col-md-6');
                $(this).closest('.row').find('input[name*="avcb_clcb_number"]').attr('disabled', false);
                $(this).closest('.row').find('input[name*="avcb_clcb_number"]').closest('.form-group').removeClass('hidden');
            } else {
                $(this).closest('.form-group') .removeClass('col-md-6').addClass('col-md-12');
                $(this).closest('.row').find('input[name*="avcb_clcb_number"]').attr('disabled', true);
                $(this).closest('.row').find('input[name*="avcb_clcb_number"]').closest('.form-group').addClass('hidden');
            }
        });

        $(document).on('change', '#operation', function (e) {
            e.preventDefault();
            const $current = $(this);
            const $row = $current.closest('.row');

            $row.find('.form-group.new_type_company, .form-group.fields_editing').addClass('hidden');
            $row.find('.form-group').removeClass('col-md-4').addClass('col-md-6');

            if ($current.val() === '2') {
                $row.find('.form-group').removeClass('col-md-6').addClass('col-md-4');
                $row.find('.form-group.fields_editing').removeClass('hidden');
            } else if ($current.val() === '4') {
                $row.find('.form-group').removeClass('col-md-6').addClass('col-md-4');
                $row.find('.form-group.new_type_company').removeClass('hidden');
            }
        });

        $(document).on('blur', '.postcode', function() {
            const $address = $(this).closest('.address');

            const $street = $address.find(".street");
            const $number = $address.find(".number");
            const $district = $address.find(".district");
            const $city = $address.find(".city");
            const $state = $address.find(".state");
            const $country = $address.find(".country");

            function limpa_formulário_cep() {
                $street.val("");
                $number.val("");
                $district.val("");
                $city.val("");
                $state.val("");
                $country.val("");
            }

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $street.val("...");
                    $number.val("...");
                    $district.val("...");
                    $city.val("...");
                    $state.val("...");
                    $country.val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $street.attr('disabled', false).val(dados.logradouro);
                            $district.attr('disabled', false).val(dados.bairro);
                            $city.attr('disabled', false).val(dados.localidade);
                            $state.attr('disabled', false).val(dados.uf);
                            $country.attr('disabled', false).val('Brasil');
                            $number.attr({
                                'disabled': false,
                                'placeholder': ''
                            }).val('').focus();
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    })()

    function init() {
        const $maskeds = $('[data-masked]');
        $maskeds.each((index, masked) => {
            $masked = $(masked);
            $masked.mask($masked.data('masked'), {
                reverse: $masked.data('masked-reverse') !== undefined
            });
        });

        const $selects = $('select');
        $selects.each((index, select) => {
            $(select).select2();
        });
    }
    init();
});
