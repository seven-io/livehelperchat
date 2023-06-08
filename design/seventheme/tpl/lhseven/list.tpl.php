<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('module/fbmessenger', 'Seven phones') ?></h1>

<?php if (isset($items)) : ?>
    <table cellpadding='0' cellspacing='0' class='table' width='100%'>
        <thead>
        <tr>
            <th width='1%'>ID</th>
            <th><?= erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('module/fbmessenger', 'Phone') ?></th>
            <th><?= erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('module/fbmessenger', 'Callback URL') ?></th>
            <th width='1%'></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><?= $item->id ?></td>
                <td><?= $item->phone ?></td>
                <td><?= $item->callback_url ?></td>
                <td nowrap>
                    <div class='btn-group' role='group' aria-label='...'
                         style='width:60px'>
                        <a class='btn btn-default btn-xs'
                           href='<?= erLhcoreClassDesign::baseurl('seven/edit') ?>/<?= $item->id ?>'>
                            <i class='material-icons mr-0'>&#xE254;</i></a>
                        <a class='btn btn-danger btn-xs csfr-required'
                           onclick='return confirm("<?= erTranslationClassLhTranslation::getInstance()
                               ->getTranslation('kernel/messages', 'Are you sure?') ?>")'
                           href='<?= erLhcoreClassDesign::baseurl('seven/delete') ?>/<?= $item->id ?>'>
                            <i class='material-icons mr-0'>&#xE872;</i></a>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <?php include erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php') ?>

    <?php if (isset($pages)) : ?>
        <?php erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php') ?>
    <?php endif ?>

<?php endif ?>

<a href='<?= erLhcoreClassDesign::baseurl('seven/new') ?>' class='btn btn-default'>
    <?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('module/fbmessenger', 'Add new Phone') ?>
</a>
