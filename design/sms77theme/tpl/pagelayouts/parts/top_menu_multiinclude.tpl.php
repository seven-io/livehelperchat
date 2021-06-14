<?php if ($currentUser->hasAccessTo('lhsms77', 'use')) : ?>
    <li class='li-icon nav-item'>
        <a
                class='nav-link'
                href='<?= erLhcoreClassDesign::baseurl('sms77/sendsms') ?>'
                title='<?= erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('pagelayout/pagelayout', 'Send SMS') ?>'
        >
            <i class='material-icons'>textsms</i>
        </a>
    </li>
<?php endif; ?>
