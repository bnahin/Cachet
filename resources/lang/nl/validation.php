<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'   => ':attribute moet worden aanvaard.',
    'active_url' => ':attribute is geen correcte URL.',
    'after'      => ':attribute moet een datum later dan :date zijn.',
    'alpha'      => ':attribute mag alleen letters bevatten.',
    'alpha_dash' => ':attribute mag alleen letters, cijfers, en streepjes bevatten.',
    'alpha_num'  => ':attribute mag enkel letters en nummers bevatten.',
    'array'      => ':attribute moet een reeks zijn.',
    'before'     => ':attribute moet een datum vóór :date zijn.',
    'between'    => [
        'numeric' => ':attribute moet een datum vóór :date zijn.',
        'file'    => 'The :attribute must be between :min and :max.',
        'string'  => 'The :attribute must be between :min and :max kilobytes.',
        'array'   => ':attribute moet tussen :min en :max items hebben.',
    ],
    'boolean'        => ':attribute moet tussen :min en :max items hebben.',
    'confirmed'      => 'The :attribute field must be true or false.',
    'date'           => 'The :attribute confirmation does not match.',
    'date_format'    => 'The :attribute is not a valid date.',
    'different'      => 'The :attribute does not match the format :format.',
    'digits'         => 'The :attribute and :other must be different.',
    'digits_between' => 'The :attribute must be :digits digits.',
    'email'          => 'The :attribute must be between :min and :max digits.',
    'exists'         => 'The :attribute must be a valid email address.',
    'distinct'       => 'The :attribute field has a duplicate value.',
    'filled'         => 'Het :attribute-formaat is ongeldig.',
    'image'          => ':attribute moet een afbeelding zijn.',
    'in'             => ':attribute moet een afbeelding zijn.',
    'in_array'       => 'The :attribute field does not exist in :other.',
    'integer'        => 'The selected :attribute is invalid.',
    'ip'             => 'The :attribute must be an integer.',
    'json'           => ':attribute moet een valide JSON tekst zijn.',
    'max'            => [
        'numeric' => 'The :attribute must be a valid IP address.',
        'file'    => 'The :attribute may not be greater than :max.',
        'string'  => 'The :attribute may not be greater than :max kilobytes.',
        'array'   => ':attribute mag niet meer dan :max items hebben.',
    ],
    'mimes' => ':attribute mag niet meer dan :max items hebben.',
    'min'   => [
        'numeric' => 'The :attribute must be a file of type: :values.',
        'file'    => ':attribute moet minstens :min kilobytes groot zijn.',
        'string'  => ':attribute moet minstens :min kilobytes groot zijn.',
        'array'   => 'The :attribute must be at least :min characters.',
    ],
    'not_in'               => 'The :attribute must have at least :min items.',
    'numeric'              => 'The selected :attribute is invalid.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute must be a number.',
    'required'             => 'Het :attribute-formaat is ongeldig.',
    'required_if'          => 'The :attribute field is required.',
    'required_unless'      => 'Het :attribute veld is verplicht tenzij :other is in :values.',
    'required_with'        => 'The :attribute field is required when :other is :value.',
    'required_with_all'    => ':attribute veld is verplicht wanneer :values aanwezig zijn.',
    'required_without'     => ':attribute veld is verplicht wanneer :values aanwezig zijn.',
    'required_without_all' => 'The :attribute field is required when :values is not present.',
    'same'                 => 'The :attribute field is required when none of :values are present.',
    'size'                 => [
        'numeric' => 'The :attribute and :other must match.',
        'file'    => ':attribute moet :size kilobytes groot zijn.',
        'string'  => ':attribute moet :size karakters zijn.',
        'array'   => ':attribute moet :size karakters zijn.',
    ],
    'string'   => 'The :attribute must contain :size items.',
    'timezone' => ':attribute moet een geldige zone zijn.',
    'unique'   => ':attribute is reeds in gebruik.',
    'url'      => 'Het :attribute-formaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
