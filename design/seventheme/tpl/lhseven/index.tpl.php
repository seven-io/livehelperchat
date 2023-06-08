<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('module/seven', 'Seven') ?></h1>

<ul>
    <li>
        <a href="<?= erLhcoreClassDesign::baseurl('seven/list') ?>">
            <?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('module/fbmessenger', 'Seven Phones') ?>
        </a>
    </li>
    <li>
        <a href="<?= erLhcoreClassDesign::baseurl('seven/sendsms') ?>">
            <?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('pagelayout/pagelayout', 'Send SMS') ?>
        </a>
    </li>
</ul>
