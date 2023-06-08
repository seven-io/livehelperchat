<?php
$Result['content'] = erLhcoreClassTemplate::getInstance('lhseven/index.tpl.php')->fetch();

$Result['path'] = [
    [
        'url' => erLhcoreClassDesign::baseurl('seven/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Seven'),
    ],
];
