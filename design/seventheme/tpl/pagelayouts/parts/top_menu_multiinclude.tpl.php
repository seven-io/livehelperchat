<?php if ($currentUser->hasAccessTo('lhseven', 'use')) : ?>
    <li class='li-icon nav-item'>
        <a
                class='nav-link'
                href='<?= erLhcoreClassDesign::baseurl('seven/sendsms') ?>'
                title='<?= erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('pagelayout/pagelayout', 'Send SMS') ?>'
        >
            <i class='material-icons'>textsms</i>
        </a>
    </li>
<?php endif; ?>
