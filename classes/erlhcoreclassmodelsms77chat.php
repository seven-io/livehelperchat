<?php

class erLhcoreClassModelSms77Chat {
    use erLhcoreClassDBTrait;

    public static $dbSessionHandler = 'erLhcoreClassExtensionSms77::getSession';
    public static $dbSortOrder = 'DESC';
    public static $dbTable = 'lhc_sms77_chat';
    public static $dbTableId = 'id';

    public $chat_id = null;
    public $ctime = null;
    public $id = null;
    public $phone = null;
    public $tphone_id = null;
    public $utime = null;

    public function getState() {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'ctime' => $this->ctime,
            'phone' => $this->phone,
            'tphone_id' => $this->tphone_id,
            'utime' => $this->utime,
        ];
    }

    public function __toString() {
        return $this->phone;
    }

    public function __get($var) {
        switch ($var) {
            case 'chat':
                $this->chat = erLhcoreClassModelChat::fetch($this->chat_id);
                return $this->chat;
            default:
                break;
        }
    }

    /**
     * Delete page chat's
     */
    public function beforeRemove() {
        $q = ezcDbInstance::get()->createDeleteQuery();
        $q->deleteFrom('lhc_sms77_chat')->where($q->expr->eq('phone', $this->phone));
        $stmt = $q->prepare();
        $stmt->execute();
    }
}
