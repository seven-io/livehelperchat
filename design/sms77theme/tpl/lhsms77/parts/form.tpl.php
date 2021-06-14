<div class='form-group'>
    <label for='phone'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'Phone number') ?>*</label>
    <input class='form-control' name='phone' id='phone'
           value='<?= htmlspecialchars($item->phone) ?>'/>
</div>

<div class='form-group'>
    <label for='base_phone'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'Base phone number') ?></label>
    <input maxlength='25' class='form-control' placeholder='E.g +1' id='base_phone'
           name='base_phone' value='<?= htmlspecialchars($item->base_phone) ?>'/>
</div>

<div class='form-group'>
    <label for='api_key'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'API Key') ?>*</label>
    <input maxlength='90' class='form-control' name='api_key' id='api_key'
           value='<?= htmlspecialchars($item->api_key) ?>'/>
</div>

<div class='form-group'>
    <label for='chat_timeout'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'Chat timeout (seconds)') ?>
        *</label>
    <input maxlength='35' class='form-control' name='chat_timeout' id='chat_timeout'
           value='<?= htmlspecialchars($item->chat_timeout) ?>'/>
    <p><i><small>How long chat is considered existing before new chat is
                created</small></i></p>
</div>

<div class='form-group'>
    <label for='responder_timeout'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger',
                'Close auto responder timeout (seconds)') ?>*</label>
    <input class='form-control' name='responder_timeout' id='responder_timeout'
           maxlength='35' value='<?= htmlspecialchars($item->responder_timeout) ?>'/>
    <p><i><small>How long we have to wait before another closed department message is send
                from last send message.</small></i></p>
</div>

<div class='form-group'>
    <label for='id_dep_id'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'Department') ?>*</label>
    <?= erLhcoreClassRenderHelper::renderCombobox([
        'css_class' => 'form-control',
        'input_name' => 'dep_id',
        'list_function' => 'erLhcoreClassModelDepartament::getList',
        'list_function_params' => [],
        'optional_field' => erTranslationClassLhTranslation::getInstance()
            ->getTranslation('chat/lists/search_panel', 'Select department'),
        'selected_id' => $item->dep_id,
    ]) ?>
</div>

<div class='form-group'>
    <label for='from'><?= erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'From') ?></label>
    <input maxlength='35' class='form-control' name='from' id='from'
           value='<?= htmlspecialchars($item->from) ?>'/>
    <p><i><small>From what phone message should be send if we could not detect original
                recipient, by default in Sms77, "from" address is the recipient
                address</small></i></p>
</div>
