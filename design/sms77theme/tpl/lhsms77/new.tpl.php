<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('module/fbmessenger', 'New Phone') ?></h1>

<?php if (isset($errors)) : ?>
    <?php include erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php') ?>
<?php endif; ?>

<form action='<?= erLhcoreClassDesign::baseurl('sms77/new') ?>' method='post'>

    <?php include erLhcoreClassDesign::designtpl('lhsms77/parts/form.tpl.php') ?>

    <div class='btn-group' role='group' aria-label='...'>
        <input type='submit' class='btn btn-default' name='Save_page'
               value='<?= erTranslationClassLhTranslation::getInstance()
                   ->getTranslation('system/buttons', 'Save') ?>'/>

        <input type='submit' class='btn btn-default' name='Cancel_page'
               value='<?= erTranslationClassLhTranslation::getInstance()
                   ->getTranslation('system/buttons', 'Cancel') ?>'/>
    </div>
</form>
