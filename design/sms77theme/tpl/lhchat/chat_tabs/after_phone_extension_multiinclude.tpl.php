<?php
$chatVars = $chat->chat_variables_array;
$lCharacters = json_encode(htmlspecialchars_decode(
    erTranslationClassLhTranslation::getInstance()
        ->getTranslation('chat/adminchat', 'characters'), ENT_QUOTES));
$lWillBeSent = json_encode(htmlspecialchars_decode(
    erTranslationClassLhTranslation::getInstance()
        ->getTranslation('chat/adminchat', 'sms will be send'), ENT_QUOTES));

if (isset($chatVars['sms77_sms_chat'])) : ?>
    <tr>
        <td><?= erTranslationClassLhTranslation::getInstance()
                ->getTranslation('chat/adminchat', 'SMS') ?></td>
        <td>
            <strong><?= erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('chat/adminchat', 'YES') ?></strong>&nbsp;
            <?php if (isset($chatVars['sms77_sms_chat_send'])) : ?>
                (<?= $chatVars['sms77_sms_chat_send']
                . '&nbsp;' . erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('chat/adminchat', 'SMS sent') ?>)
            <?php endif ?>

            <script>
                $('#CSChatMessage-<?= $chat->id ?>').keyup(function () {
                    var length = $(this).val().length;

                    $('#user-is-typing-' + <?= $chat->id ?>).html(length + ' ' +
                        <?= $lCharacters ?> + ', ' + Math.ceil(length / 160) + ' '
                        + <?= $lWillBeSent ?>).css('visibility', 'visible');
                });
            </script>
        </td>
    </tr>
<?php endif; ?>
