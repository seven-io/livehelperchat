<?php
$tpl = erLhcoreClassTemplate::getInstance('lhseven/edit.tpl.php');
$item = erLhcoreClassModelSevenPhone::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {
    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('seven/list');
        exit;
    }

    $Errors = erLhcoreClassSevenValidator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('seven/list');
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
        'url' => erLhcoreClassDesign::baseurl('seven/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Seven Phones'),
    ],
    [
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Edit phone'),
    ],
];
