<?php
$tpl = erLhcoreClassTemplate::getInstance('lhsms77/sendsms.tpl.php');
$input = new stdClass;
$input->create_chat = true;
$input->dep_id = 0;
$input->message = '';
$input->phone_number = '';
$input->sms77_phone_id = 0;

if (ezcInputForm::hasPostData()) {
    $Errors = [];
    $form = new ezcInputForm(INPUT_POST, [
        'Sms77CreateChat' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean', null),
        'Sms77Department' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', null),
        'Sms77PhoneId' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', null),
        'Sms77Message' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw', null),
        'Sms77PhoneNumber' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw', null),
    ]);

    if (!empty($form->Sms77PhoneNumber) && $form->hasValidData('Sms77PhoneNumber'))
        $input->phone_number = $form->Sms77PhoneNumber;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please enter phone number!');

    if (!empty($form->Sms77Message) && $form->hasValidData('Sms77Message'))
        $input->message = $form->Sms77Message;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please enter message!');

    $input->create_chat = // should we create a chat or just send an SMS
        $form->hasValidData('Sms77CreateChat') && $form->Sms77CreateChat;

    if ($form->hasValidData('Sms77PhoneId')) $input->sms77_phone_id = $form->Sms77PhoneId;
    else
        $Errors[] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please choose Sms77 phone!');

    if ($form->hasValidData('Sms77Department')) $input->dep_id = $form->Sms77Department;

    if (empty($Errors))
        try {
            $currentUser = erLhcoreClassUser::instance();
            $userData = $currentUser->getUserData();
            $tpl->set('updated', true);
            /** @var erLhcoreClassExtensionSms77 $c */
            $c = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSms77');
            $tpl->set('chat', $c->sendManualMessage([
                'create_chat' => $input->create_chat,
                'dep_id' => $input->dep_id,
                'msg' => $input->message,
                'name_support' => $userData->name_support,
                'operator_id' => $userData->id,
                'phone_number' => $input->phone_number,
                'sms77_phone_id' => $input->sms77_phone_id,
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
        ->getTranslation('sugarcrm/module', 'Send SMS via Sms77'),
]];
