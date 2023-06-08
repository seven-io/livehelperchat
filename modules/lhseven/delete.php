<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

try {
    $item = erLhcoreClassModelSevenPhone::fetch($Params['user_parameters']['id']);
    $item->removeThis();
    erLhcoreClassModule::redirect('seven/list');
    exit;

} catch (Exception $e) {
    print_r($e);
    $tpl = erLhcoreClassTemplate::getInstance('lhkernel/validation_error.tpl.php');
    $tpl->set('errors', [$e->getMessage()]);
    $Result['content'] = $tpl->fetch();
}
