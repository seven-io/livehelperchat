<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sugarcrm/module', 'Send SMS via Seven') ?></h1>

<small><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('seven/module', 'Required fields are marked with a *') ?></small>

<hr>

<?php if (isset($errors)): ?>
    <?php include erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php') ?>
<?php endif ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()
    ->getTranslation('seven/module', 'Message sent!') ?>
    <?php include erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php') ?>
<?php endif ?>

<?php if (isset($chat)): ?>
    <a title='<?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sugarcrm/module', 'Open in a new window') ?>'
       ng-click='lhc.startChatNewWindow(<?= $chat->id ?>)'>
        <?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('seven/module', 'Open chat') ?>
    </a>
<?php endif ?>

<form action='' method='post'>
    <div class='form-group'>
        <label for='SevenPhoneNumber'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('seven/module', 'Phone number') ?>*</label>
        <input class='form-control'
               id='SevenPhoneNumber'
               name='SevenPhoneNumber'
               placeholder='<?= erTranslationClassLhTranslation::getInstance()
                   ->getTranslation('sugarcrm/module', 'Phone number') ?>'
               required
               type='text'
               value='<?= htmlspecialchars($input->phone_number) ?>'
        />
    </div>

    <div class='form-group'>
        <label for='SevenMessage'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('seven/module', 'Message') ?>*</label>
        <textarea class='form-control'
                  id='SevenMessage'
                  name='SevenMessage'
                  required><?= htmlspecialchars($input->message) ?></textarea>
    </div>

    <div class='form-group'>
        <label for='id_SevenDepartment'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Department') ?></label>
        <?= erLhcoreClassRenderHelper::renderCombobox([
            'css_class' => 'form-control',
            'input_name' => 'SevenDepartment',
            'list_function' => 'erLhcoreClassModelDepartament::getList',
            'optional_field' => erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Choose department'),
            'selected_id' => $input->dep_id,
        ]) ?>
    </div>

    <div class='form-group'>
        <label for='id_SevenPhoneId'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Phone Number') ?></label>
        <?= erLhcoreClassRenderHelper::renderCombobox([
            'css_class' => 'form-control',
            'display_name' => 'phone',
            'input_name' => 'SevenPhoneId',
            'list_function' => 'erLhcoreClassModelSevenPhone::getList',
            'optional_field' => erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Choose'),
            'selected_id' => $input->seven_phone_id,
        ]) ?>
    </div>

    <div class='form-group'>
        <label>
            <input name='SevenCreateChat'
                   type='checkbox'
                   value='on'
                   <?php if ($input->create_chat): ?>checked<?php endif ?>
            />
            <?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('sugarcrm/module', 'Create chat') ?>
        </label>
    </div>

    <input class='btn btn-primary'
           name='Update'
           type='submit'
           value='<?= erTranslationClassLhTranslation::getInstance()
               ->getTranslation('seven/module', 'Send') ?>'
    />
</form>
