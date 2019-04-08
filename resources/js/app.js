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
    const $forms = $('[data-form-process]');
    if ($forms.length) {
        $forms.each(function (index, form) {
            const submitForm = () => {
                const $form = $(form);
                const formData = new FormData(form);
                window.axios.post($form.attr('action'), formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                }).then(response => {
                    console.log(response)
                })
                .catch(error => {
                    console.log(error)
                });
            };

            $(form).steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                autoFocus: true,
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
                        submitForm();
                    }
                },
                onFinished: () => {
                    submitForm();
                }
            }).validate({
                rules: {
                    'owner[cpf]': { cpfBR: true },
                    'owner[address][postcode]': { postalcodeBR: true },
                    'company[address][postcode]': { postalcodeBR: true },
                    'owner[rg_expedition]': { dateITA: true },
                    'company[signed]': { dateITA: true }
                }
            });
        });
    }

    const $maskeds = $('[data-masked]');
    $maskeds.each((index, masked) => {
        $masked = $(masked);
        $masked.mask($masked.data('masked'), {
            reverse: $masked.data('masked-reverse') !== undefined
        });
    });

    $('.postcode').blur(function() {
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

});
