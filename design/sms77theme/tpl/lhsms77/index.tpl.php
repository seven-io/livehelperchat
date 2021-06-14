<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('module/sms77', 'Sms77') ?></h1>

<ul>
    <li>
        <a href="<?= erLhcoreClassDesign::baseurl('sms77/list') ?>">
            <?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('module/fbmessenger', 'Sms77 Phones') ?>
        </a>
    </li>
    <li>
        <a href="<?= erLhcoreClassDesign::baseurl('sms77/sendsms') ?>">
            <?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('pagelayout/pagelayout', 'Send SMS') ?>
        </a>
    </li>
</ul>
