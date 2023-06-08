<?php
$tpl = erLhcoreClassTemplate::getInstance('lhseven/sendsms.tpl.php');
$input = new stdClass;
$input->create_chat = true;
$input->dep_id = 0;
$input->message = '';
$input->phone_number = '';
$input->seven_phone_id = 0;

if (ezcInputForm::hasPostData()) {
    $Errors = [];
    $form = new ezcInputForm(INPUT_POST, [
        'SevenCreateChat' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean', null),
        'SevenDepartment' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', null),
        'SevenPhoneId' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', null),
        'SevenMessage' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw', null),
        'SevenPhoneNumber' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw', null),
    ]);

    if (!empty($form->SevenPhoneNumber) && $form->hasValidData('SevenPhoneNumber'))
        $input->phone_number = $form->SevenPhoneNumber;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please enter phone number!');

    if (!empty($form->SevenMessage) && $form->hasValidData('SevenMessage'))
        $input->message = $form->SevenMessage;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please enter message!');

    $input->create_chat = // should we create a chat or just send an SMS
        $form->hasValidData('SevenCreateChat') && $form->SevenCreateChat;

    if ($form->hasValidData('SevenPhoneId')) $input->seven_phone_id = $form->SevenPhoneId;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please choose Seven phone!');

    if ($form->hasValidData('SevenDepartment')) $input->dep_id = $form->SevenDepartment;

    if (empty($Errors))
        try {
            $currentUser = erLhcoreClassUser::instance();
            $userData = $currentUser->getUserData();
            $tpl->set('updated', true);
            /** @var erLhcoreClassExtensionSeven $c */
            $c = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSeven');
            $tpl->set('chat', $c->sendManualMessage([
                'create_chat' => $input->create_chat,
                'dep_id' => $input->dep_id,
                'msg' => $input->message,
                'name_support' => $userData->name_support,
                'operator_id' => $userData->id,
                'phone_number' => $input->phone_number,
                'seven_phone_id' => $input->seven_phone_id,
            ]));
        } catch (Exception $e) {
            $tpl->set('errors', [$e->getMessage()]);
        }
    else $tpl->set('errors', $Errors);
}

$tpl->set('input', $input);

$Result['content'] = $tpl->fetch();
$Result['path'] = [[
    'title' => erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sugarcrm/module', 'Send SMS via Seven'),
]];
