<?php
$tpl = erLhcoreClassTemplate::getInstance('lhseven/new.tpl.php');
$item = new erLhcoreClassModelSevenPhone;
$tpl->set('item', $item);

if (ezcInputForm::hasPostData()) {
    $Errors = erLhcoreClassSevenValidator::validatePhone($item);

    if (!count($Errors)) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('seven/list');
            exit;
        } catch (Exception $e) {
            $tpl->set('errors', [$e->getMessage()]);
        }

    } else $tpl->set('errors', $Errors);
}

$Result['content'] = $tpl->fetch();
$Result['path'] = [
    [
        'url' => erLhcoreClassDesign::baseurl('seven/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Seven Phones'),
    ],
    [
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'New'),
    ],
];
