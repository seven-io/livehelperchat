<?php
$Result['content'] = erLhcoreClassTemplate::getInstance('lhsms77/index.tpl.php')->fetch();

$Result['path'] = [
    [
        'url' => erLhcoreClassDesign::baseurl('sms77/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'Sms77'),
    ],
];
