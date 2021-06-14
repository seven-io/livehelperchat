<?php
/*
erLhcoreClassLog::write(var_dump($_POST));

$_POST['id'] = '681590';
$_POST['sender'] = '+14803607305';
$_POST['system'] = '491716992343';
$_POST['text'] = 'HI2U';
$_POST['time'] = '1605878104';
*/

try {
    /** @var erLhcoreClassExtensionSms77 $class */
    $class = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSms77');
    $class->processCallback();
} catch (Exception $e) {
    erLhcoreClassLog::write(var_dump($_POST, $e));
    throw $e;
}

header("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?><Response></Response>';
exit;
