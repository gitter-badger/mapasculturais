<?php
/**
 * See https://github.com/Respect/Validation to know how to write validations
 */
return array(
    'metadata' => array(
        'nomeCompleto' => array(
            'label' => 'Nome completo ou Razão Social',
            'validations' => array(
                //'required' => 'Seu nome completo ou jurídico deve ser informado.'
            )
        ),

        'documento' => array(
            'label' => 'CPF ou CNPJ',
//            'validations' => array(
//                'required' => 'Seu CPF ou CNPJ deve ser informado.',
//                'unique' => 'Este documento já está registrado em nosso sistema.',
//                'v::oneOf(v::cpf(), v::cnpj())' => 'O número de documento informado é inválido.',
//                'v::regex("#^(\d{2}(\.\d{3}){2}/\d{4}-\d{2})|(\d{3}\.\d{3}\.\d{3}-\d{2})$#")' => 'Utilize o formato xxx.xxx.xxx-xx para CPF e xx.xxx.xxx/xxxx-xx para CNPJ.'
//            )
        ),

        'idade' => array(
            'label' => 'Idade',
            'validations' => array(
                "v::int()->positive()" => "A idade/tempo deve ser um número positivo."
            )
        ),

        'precisao' => array(
            'label' => 'Localização',
            'type' => 'select',
            'options' => array(
                '' => 'Não Informar',
                'Precisa' => 'Precisa',
                'Aproximada' => 'Aproximada'
            )
        ),

        'genero' => array(
            'label' => 'Gênero',
            'type' => 'select',
            'options' => array(
                '' => 'Não Informar',
                'Feminino' => 'Feminino',
                'Masculino' => 'Masculino'
            )
        ),

        'emailPublico' => array(
            'label' => 'Email Público',
            'validations' => array(
                'v::email()' => 'O email público não é um email válido.'
            )
        ),

        'emailPrivado' => array(
            'label' => 'Email Privado',
            'private' => true,
            'validations' => array(
                //'required' => 'O email privado é obrigatório.',
                'v::email()' => 'O email privado não é um email válido.'
            )
        ),

        'telefonePublico' => array(
            'label' => 'Telefone Público',
            'type' => 'string',
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => 'Por favor, informe o telefone público no formato (xx) xxxx-xxxx.'
            )
        ),

        'telefone1' => array(
            'label' => 'Telefone 1',
            'type' => 'string',
            'private' => true,
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => 'Por favor, informe o telefone 1 no formato (xx) xxxx-xxxx.'
            )
        ),


        'telefone2' => array(
            'label' => 'Telefone 2',
            'type' => 'string',
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => 'Por favor, informe o telefone 2 no formato (xx) xxxx-xxxx.'
            )
        ),

        'endereco' => array(
            'label' => 'Endereço',
            'type' => 'text'
        ),

        'site' => array(
            'label' => 'Site',
            'validations' => array(
                "v::url()" => "A url informada é inválida."
            )
        ),
        'facebook' => array(
            'label' => 'Facebook',
            'validations' => array(
                "v::url('facebook.com')" => "A url informada é inválida."
            )
        ),
        'twitter' => array(
            'label' => 'Twitter',
            'validations' => array(
                "v::url('twitter.com')" => "A url informada é inválida."
            )
        ),
        'googleplus' => array(
            'label' => 'Google+',
            'validations' => array(
                "v::url('plus.google.com')" => "A url informada é inválida."
            )
        ),

        'sp_regiao' => array('label' => 'Região',),
        'sp_subprefeitura' => array('label' => 'Subprefeitura',),
        'sp_distrito' => array('label' => 'Distrito',),

    ),
    'items' => array(
        1 => array( 'name' => 'Individual' ),
        2 => array( 'name' => 'Coletivo' ),
    )
);
