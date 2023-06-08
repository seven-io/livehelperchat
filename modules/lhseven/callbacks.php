<?php

try {
    /** @var erLhcoreClassExtensionSeven $class */
    $class = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionSeven');
    $class->processCallback();
} catch (Exception $e) {
    erLhcoreClassLog::write(var_dump($_POST, $e));
    throw $e;
}

header("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?><Response></Response>';
exit;
