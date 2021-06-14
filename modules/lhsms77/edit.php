<?php
$tpl = erLhcoreClassTemplate::getInstance('lhsms77/edit.tpl.php');
$item = erLhcoreClassModelSms77Phone::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {
    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('sms77/list');
        exit;
    }

    $Errors = erLhcoreClassSms77Validator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('sms77/list');
            exit;

        } catch (Exception $e) {
            $tpl->set('errors', [$e->getMessage()]);
        }

    } else $tpl->set('errors', $Errors);
}

$tpl->setArray(['item' => $item]);

$Result['content'] = $tpl->fetch();
$Result['path'] = [
    [
        'url' => erLhcoreClassDesign::baseurl('sms77/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Sms77 Phones'),
    ],
    [
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Edit phone'),
    ],
];
