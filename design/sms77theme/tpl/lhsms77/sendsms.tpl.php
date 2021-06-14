<h1><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sugarcrm/module', 'Send SMS via Sms77') ?></h1>

<small><?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sms77/module', 'Required fields are marked with a *') ?></small>

<hr>

<?php if (isset($errors)): ?>
    <?php include erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php') ?>
<?php endif ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()
    ->getTranslation('sms77/module', 'Message sent!') ?>
    <?php include erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php') ?>
<?php endif ?>

<?php if (isset($chat)): ?>
    <a title='<?= erTranslationClassLhTranslation::getInstance()
        ->getTranslation('sugarcrm/module', 'Open in a new window') ?>'
       ng-click='lhc.startChatNewWindow(<?= $chat->id ?>)'>
        <?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('sms77/module', 'Open chat') ?>
    </a>
<?php endif ?>

<form action='' method='post'>
    <div class='form-group'>
        <label for='Sms77PhoneNumber'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('sms77/module', 'Phone number') ?>*</label>
        <input class='form-control'
               id='Sms77PhoneNumber'
               name='Sms77PhoneNumber'
               placeholder='<?= erTranslationClassLhTranslation::getInstance()
                   ->getTranslation('sugarcrm/module', 'Phone number') ?>'
               required
               type='text'
               value='<?= htmlspecialchars($input->phone_number) ?>'
        />
    </div>

    <div class='form-group'>
        <label for='Sms77Message'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('sms77/module', 'Message') ?>*</label>
        <textarea class='form-control'
                  id='Sms77Message'
                  name='Sms77Message'
                  required><?= htmlspecialchars($input->message) ?></textarea>
    </div>

    <div class='form-group'>
        <label for='id_Sms77Department'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Department') ?></label>
        <?= erLhcoreClassRenderHelper::renderCombobox([
            'css_class' => 'form-control',
            'input_name' => 'Sms77Department',
            'list_function' => 'erLhcoreClassModelDepartament::getList',
            'optional_field' => erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Choose department'),
            'selected_id' => $input->dep_id,
        ]) ?>
    </div>

    <div class='form-group'>
        <label for='id_Sms77PhoneId'><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Phone Number') ?></label>
        <?= erLhcoreClassRenderHelper::renderCombobox([
            'css_class' => 'form-control',
            'display_name' => 'phone',
            'input_name' => 'Sms77PhoneId',
            'list_function' => 'erLhcoreClassModelSms77Phone::getList',
            'optional_field' => erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/lists/search_panel', 'Choose'),
            'selected_id' => $input->sms77_phone_id,
        ]) ?>
    </div>

    <div class='form-group'>
        <label>
            <input name='Sms77CreateChat'
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
               ->getTranslation('sms77/module', 'Send') ?>'
    />
</form>
