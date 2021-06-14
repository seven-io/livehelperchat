<?php

return [
    'filterAttributes' => ['name' => [
        'filter_table_field' => 'name',
        'filter_type' => 'like',
        'required' => false,
        'type' => 'text',
        'trans' => 'Name',
        'valid_if_filled' => false,
        'validation_definition' => new ezcInputFormDefinitionElement (
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
    ]],
    'sortAttributes' => [
        'default' => false,
        'disabled' => true,
        'field' => false,
        'options' => [],
        'serialised' => true,
    ],
];
