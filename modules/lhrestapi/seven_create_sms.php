<?php

try {
    erLhcoreClassRestAPIHandler::validateRequest();

    $user = erLhcoreClassRestAPIHandler::getUser();

    if (!$user->hasAccessTo('lhseven', 'use'))
        throw new Exception('You do not have permission to use seven! "lhseven", "use" permission is missing');

    $params = json_decode(file_get_contents('php://input'), true);

    // seven phone number
    if (!isset($params['phone_number']) || empty($params['phone_number']))
        $Errors['phone_number'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please enter phone number!');

    // seven message
    if (!isset($params['msg']) || empty($params['msg']))
        $Errors['msg'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please enter message!');

    $params['create_chat'] = isset($params['create_chat']);

    if (!isset($params['seven_phone_id']) || !is_numeric($params['seven_phone_id']))
        $Errors['seven_phone_id'] = erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/sendsms', 'Please enter seven phone!');

    if (!empty($Errors)) throw new Exception(json_encode($Errors));

    $seven = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSeven');

    $params['name_support'] = $user->name_support;
    $params['operator_id'] = $user->id;

    $chat = $seven->sendManualMessage($params);

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
