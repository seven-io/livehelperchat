<?php

try {
    erLhcoreClassRestAPIHandler::validateRequest();

    $user = erLhcoreClassRestAPIHandler::getUser();

    if (!$user->hasAccessTo('lhsms77', 'use'))
        throw new Exception('You do not have permission to use sms77! "lhsms77", "use" permission is missing');

    $params = json_decode(file_get_contents('php://input'), true);

    // Sms77 phone number
    if (!isset($params['phone_number']) || empty($params['phone_number']))
        $Errors['phone_number'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please enter phone number!');

    // Sms77 message
    if (!isset($params['msg']) || empty($params['msg']))
        $Errors['msg'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please enter message!');

    $params['create_chat'] = isset($params['create_chat']);

    if (!isset($params['sms77_phone_id']) || !is_numeric($params['sms77_phone_id']))
        $Errors['sms77_phone_id'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/sendsms', 'Please enter sms77 phone!');

    if (!empty($Errors)) throw new Exception(json_encode($Errors));

    $sms77 = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSms77');

    $params['name_support'] = $user->name_support;
    $params['operator_id'] = $user->id;

    $chat = $sms77->sendManualMessage($params);

    echo erLhcoreClassRestAPIHandler::outputResponse([
        'error' => false,
        'result' => 'ok',
    ]);
} catch (Exception $e) {
    erLhcoreClassLog::write(var_dump($params, $e));
    http_response_code(400);
    echo erLhcoreClassRestAPIHandler::outputResponse([
        'error' => true,
        'result' => $e->getMessage(),
    ]);
}
exit;
