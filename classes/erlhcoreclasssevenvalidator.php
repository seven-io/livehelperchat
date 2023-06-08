<?php

class erLhcoreClassSevenValidator {
    public static function validatePhone(erLhcoreClassModelSevenPhone $item) {
        $form = new ezcInputForm(INPUT_POST, [
            'api_key' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'base_phone' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'chat_timeout' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'dep_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', ['min_range' => 1]
            ),
            'from' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'phone' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'responder_timeout' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
        ]);
        $Errors = [];

        if ($form->hasValidData('phone') && $form->phone != '')
            $item->phone = $form->phone;
        else
            $Errors[] = erTranslationClassLhTranslation::getInstance()
                ->getTranslation('xmppservice/operatorvalidator',
                    'Please enter phone number!');

        $item->base_phone = $form->hasValidData('base_phone') && $form->base_phone !== ''
            ? $form->base_phone : '';

        if ($form->hasValidData('api_key') && $form->api_key != '')
            $item->api_key = $form->api_key;
        else
            $Errors[] = erTranslationClassLhTranslation::getInstance()
                ->getTranslation('xmppservice/operatorvalidator',
                    'Please enter Auth Token!');

        $item->from = $form->hasValidData('from') && $form->from != '' ? $form->from : '';

        if ($form->hasValidData('dep_id')) $item->dep_id = $form->dep_id;
        else
            $Errors[] = erTranslationClassLhTranslation::getInstance()
                ->getTranslation('xmppservice/operatorvalidator',
                    'Please choose a department!');

        if ($form->hasValidData('chat_timeout'))
            $item->chat_timeout = $form->chat_timeout;
        else
            $Errors[] = erTranslationClassLhTranslation::getInstance()
                ->getTranslation('xmppservice/operatorvalidator',
                    'Please enter chat timeout!');

        $item->responder_timeout =
            $form->hasValidData('responder_timeout') ? $form->responder_timeout : 0;

        return $Errors;
    }
}
