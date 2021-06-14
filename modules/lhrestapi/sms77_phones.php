<?php

try {
    erLhcoreClassRestAPIHandler::validateRequest();

    if (!erLhcoreClassRestAPIHandler::getUser()->hasAccessTo('lhsms77', 'use'))
        throw new Exception('You do not have permission to use sms77! "lhsms77"; "use" permission is missing');

    echo erLhcoreClassRestAPIHandler::outputResponse([
        'error' => false,
        'result' => array_values(erLhcoreClassModelSms77Phone::getList([
            'ignore_fields' => ['api_key'],
            'limit' => false,
        ])),
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo erLhcoreClassRestAPIHandler::outputResponse([
        'error' => true,
        'result' => $e->getMessage(),
    ]);
}
exit;
