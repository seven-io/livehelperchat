<?php
$tpl = erLhcoreClassTemplate::getInstance('lhsms77/new.tpl.php');
$item = new erLhcoreClassModelSms77Phone;
$tpl->set('item', $item);

if (ezcInputForm::hasPostData()) {
    $Errors = erLhcoreClassSms77Validator::validatePhone($item);

    if (!count($Errors)) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('sms77/list');
            exit;
        } catch (Exception $e) {
            $tpl->set('errors', [$e->getMessage()]);
        }

    } else $tpl->set('errors', $Errors);
}

$Result['content'] = $tpl->fetch();
$Result['path'] = [
    [
        'url' => erLhcoreClassDesign::baseurl('sms77/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Sms77 Phones'),
    ],
    [
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'New'),
    ],
];
