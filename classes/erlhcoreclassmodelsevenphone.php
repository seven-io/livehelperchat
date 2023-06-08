<?php

class erLhcoreClassModelSevenPhone {
    use erLhcoreClassDBTrait;

    public static $dbSessionHandler = 'erLhcoreClassExtensionSeven::getSession';
    public static $dbSortOrder = 'DESC';
    public static $dbTable = 'lhc_seven_phone';
    public static $dbTableId = 'id';

    public $api_key;
    public $base_phone = '';
    public $chat_timeout = 3600 * 72; // 72 hours
    public $dep_id;
    public $from = '';
    public $id;
    public $phone;
    public $responder_timeout = 12 * 3600; // 12 hours

    public function getState() {
        return [
            'api_key' => $this->api_key,
            'base_phone' => $this->base_phone,
            'chat_timeout' => $this->chat_timeout,
            'id' => $this->id,
            'dep_id' => $this->dep_id,
            'from' => $this->from,
            'phone' => $this->phone,
            'responder_timeout' => $this->responder_timeout,
        ];
    }

    public function __toString() {
        return $this->phone;
    }

    public function __get($var) {
        switch ($var) {
            case 'callback_url':
                $this->callback_url = erLhcoreClassXMP::getBaseHost()
                    . $_SERVER['HTTP_HOST']
                    . erLhcoreClassDesign::baseurldirect('seven/callbacks');

                return $this->callback_url;
            default:
                break;
        }
    }

    /** Delete page chat's */
    public function beforeRemove() {
        $q = ezcDbInstance::get()->createDeleteQuery();
        $q->deleteFrom('lhc_seven_chat')->where(
            $q->expr->eq('phone', ezcDbInstance::get()->quote($this->phone)));
        $stmt = $q->prepare();
        $stmt->execute();
    }
}
