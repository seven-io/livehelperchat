<?php

class erLhcoreClassExtensionSms77 {
    private static $persistentSession;
    private $ahinstance;
    private $settings = [];

    public static function getSession() {
        if (!isset (self::$persistentSession))
            self::$persistentSession = new ezcPersistentSession(ezcDbInstance::get(),
                new ezcPersistentCodeManager ('./extension/sms77/pos'));
        return self::$persistentSession;
    }

    public function run() {
        spl_autoload_register(static function ($class) {
            $classes = [
                'erLhcoreClassModelSms77Chat' =>
                    'extension/sms77/classes/erlhcoreclassmodelsms77chat.php',
                'erLhcoreClassModelSms77Phone' =>
                    'extension/sms77/classes/erlhcoreclassmodelsms77phone.php',
                'erLhcoreClassSms77Validator' =>
                    'extension/sms77/classes/erlhcoreclasssms77validator.php',
            ];

            if (array_key_exists($class, $classes)) require_once $classes[$class];
        });

        $this->settings = include 'extension/sms77/settings/settings.ini.php';
        require_once 'extension/sms77/vendor/autoload.php';

        if ($this->settings['ahenviroment'] === true)
            $this->ahinstance = erLhcoreClassInstance::getInstance();

        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        if (null === $dispatcher)
            throw new Exception("Failed to init erLhcoreClassChatEventDispatcher");
        $dispatcher->listen('chat.desktop_client_admin_msg', [$this, 'sendSMSUser']);
        $dispatcher->listen('chat.web_add_msg_admin', [$this, 'sendSMSUser']);
        $dispatcher->listen('restapi.chats_filter', function (array &$params) {
            if (isset($_GET['sms77_sms_chat'])
                && (string)$_GET['sms77_sms_chat'] === 'true')
                $params['filter']['innerjoin']['lhc_sms77_chat'] =
                    ['`lh_chat`.`id`', '`lhc_sms77_chat`.`chat_id`'];
        });
        $dispatcher->listen('restapi.swagger', function (array &$params) {
            $params['chats_parameters'] .= '{
            "default": false,
            "description": "Include only sms77 sms chats",
            "in": "query",
            "name": "sms77_sms_chat",
            "type": "boolean",
            "required": false
        },';

            $params['append_paths'] .= ',"/restapi/sms77_create_sms": {
      "post": {
        "description": "",
        "produces": ["application/json"],
        "summary": "Send SMS to visitor",
        "tags": ["sms77"],
        "parameters": [
            {
              "in": "body",
              "name": "body",
              "description": "Bot object that needs to be added to the lhc",
              "required": true,
              "schema": {
                "$ref": "#/definitions/Sms77SMS"
              }
            }
        ],
        "responses": {
          "200": {
            "description": "Sends SMS To visitor",
            "schema": {
            }
          },
          "400": {
            "description": "Error",
            "schema": {
            }
          }
        },
        "security": [
          {
            "login": []
          }
        ]
      }
    },"/restapi/sms77_phones": {
      "get": {
        "tags": [
          "sms77"
        ],
        "summary": "Returns list of registered Sms77 phones",
        "description": "",
        "produces": [
          "application/json"
        ],
        "parameters": [            
        ],
        "responses": {
          "200": {
            "description": "List of registered Sms77 phones returned",
            "schema": {
            }
          },
          "400": {
            "description": "Error",
            "schema": {
            }
          }
        },
        "security": [
          {
            "login": []
          }
        ]
      }
    }';

            $params['append_definitions'] .= '"Sms77SMS": {
      "type": "object",
      "properties": {
        "msg": {
          "default": "message to visitor",
          "description": "Message",
          "required": true,
          "type": "string",
        },
        "phone_number": {
          "default": "",
          "description": "Phone number",
          "required": true,
          "type": "string"
        },
        "create_chat": {
          "description": "Create chat then message is send",
          "example": null
          "required": false,
          "type": "boolean"
        },
        "dep_id": {
          "description": "Department ID",
          "example": null,
          "required": false,
          "type": "string"
        },
        "sms77_phone_id": {
          "description": "Sms77 phone ID",
          "required": true,
          "type": "string"
        }
      },
      "example" : {
        "create_chat" : true,
        "msg" : "Message to visitor",
        "phone_number" : "+37065272xxx",
        "sms77_phone_id" : 1
      }
    },';
        });
        $dispatcher->listen('telegram.msg_received', [$this, 'sendSMSUser']);
        $dispatcher->listen('xml.lists', function (array &$params) {
            $chats = erLhcoreClassModelChat::getList([
                'filterin' => ['status' => [0, 1]],
                'innerjoin' => ['lhc_sms77_chat' =>
                    ['`lh_chat`.`id`', '`lhc_sms77_chat`.`chat_id`']],
                'limit' => 10,
                'sort' => '`lh_chat`.`id` DESC',
            ]);

            erLhcoreClassChat::prefillGetAttributes($chats,
                ['department_name', 'user_status_front', 'phone'],
                ['updateIgnoreColumns', 'department', 'user']);

            $params['list']['sms77_chats'] = [
                'column_names' => [
                    'chat_locale' => 'Visitor language',
                    'city' => 'City',
                    'country_name' => 'Country',
                    'department_name' => 'Department',
                    'email' => 'E-mail',
                    'id' => 'ID',
                    'ip' => 'IP',
                    'name' => 'Department',
                    'nick' => 'Nick',
                    'phone' => 'Phone',
                    'priority' => 'Priority',
                    'referrer' => 'Referrer',
                    'session_referrer' => 'Original referrer',
                    'time' => 'Time',
                    'user_tz_identifier' => 'User time zone',
                    'wait_time' => 'Waited',
                ],
                'hidden_columns' => [
                    'additional_data',
                    'chat_duration',
                    'chat_initiator',
                    'chat_variables',
                    'country_code',
                    'dep_id',
                    'fbst',
                    'has_unread_messages',
                    'hash',
                    'last_msg_id',
                    'last_user_msg_time',
                    'lat',
                    'lon',
                    'lsync',
                    'mail_send',
                    'na_cb_executed',
                    'nc_cb_executed',
                    'online_user_id',
                    'operation',
                    'operation_admin',
                    'operator_typing',
                    'operator_typing_id',
                    'pnd_time',
                    'reinform_timeout',
                    'remarks',
                    'screenshot_id',
                    'status',
                    'status_sub',
                    'support_informed',
                    'support_informed',
                    'timeout_message',
                    'transfer_if_na',
                    'transfer_timeout_ac',
                    'transfer_timeout_ts',
                    'tslasign',
                    'uagent',
                    'unanswered_chat',
                    'unread_messages_informed',
                    'user_closed_ts',
                    'user_id',
                    'user_status',
                    'user_status_front',
                    'user_typing',
                    'user_typing_txt',
                    'wait_timeout',
                    'wait_timeout_send',
                ],
                'rows' => array_values($chats),
                'size' => count($chats),
                'timestamp_delegate' => ['time'],
            ];
        });
    }

    /**
     * @desc Then operator sends a message parse message for attached files etc.
     * @desc Sends SMS to user as manual action.
     */
    public function sendManualMessage($params) {
        $tPhone = erLhcoreClassModelSms77Phone::fetch($params['sms77_phone_id']);

        // Prepend Signature if Telegram extension is used
        $signatureText = '';
        $statusSignature = erLhcoreClassChatEventDispatcher::getInstance()
            ->dispatch('telegram.get_signature',
                ['user_id' => erLhcoreClassUser::instance()->getUserID()]);
        if ($statusSignature !== false) $signatureText = $statusSignature['signature'];

        $recipientPhone = str_replace($tPhone->base_phone, '', $params['phone_number']);

        if (strpos($recipientPhone, '+') === false)
            $recipientPhone = $tPhone->base_phone . $recipientPhone;

        $text = $params['msg'] . $signatureText;

        $this->initClient($tPhone->api_key)->sms($recipientPhone, $text,
            ['from' => $tPhone->base_phone . $tPhone->from]);

        if ((bool)$params['create_chat'] !== true) return;

        $chat = new erLhcoreClassModelChat;
        $chat->phone = str_replace($tPhone->base_phone, '', $params['phone_number']);
        $chat->dep_id = isset($params['dep_id']) ? $params['dep_id'] : 0;

        if ((int)$chat->dep_id === 0) {
            if ($tPhone->dep_id > 0) $chat->dep_id = $tPhone->dep_id;
            else {
                $departments = erLhcoreClassModelDepartament::getList([
                    'limit' => 1,
                    'filter' => [
                        'disabled' => 0,
                    ],
                ]);

                if (!empty($departments)) {
                    $department = array_shift($departments);
                    $chat->dep_id = $department->id;
                    $chat->priority = $department->priority;
                } else throw new Exception('Could not detect default department');
            }
        }

        $chat->chat_variables = json_encode([
            'sms77_from' => $tPhone->from,
            'sms77_phone_id' => $tPhone->id,
            'sms77_sms_chat' => true,
        ]);
        $chat->hash = erLhcoreClassChat::generateHash();
        $chat->nick = erTranslationClassLhTranslation::getInstance()
                ->getTranslation('sms77/sms', 'SMS') . ' ' . $chat->phone;
        $chat->referrer = '';
        $chat->session_referrer = '';
        $chat->status = 1;
        $chat->time = time();
        $chat->saveThis();

        self::newChatModel($chat,
            str_replace($tPhone->base_phone, '', $params['phone_number']), $tPhone);

        $msg = self::newModelMsg(
            $chat, $text, $params['operator_id'], $params['name_support'], 5);

        $chat->last_msg_id = $msg->id;
        $chat->last_user_msg_time = $msg->time;
        $chat->saveThis();

        erLhcoreClassChatEventDispatcher::getInstance()
            ->dispatch('chat.chat_started', ['chat' => &$chat]); // Execute standard callback as chat was started

        return $chat;
    }

    /**
     * @param string $apiKey
     * @return Sms77\Api\Client
     */
    private function initClient($apiKey) {
        return new Sms77\Api\Client($apiKey, 'LiveHelperChat');
    }

    private static function newChatModel($chat, $phone, $tPhone) {
        $tChat = new erLhcoreClassModelSms77Chat;
        $tChat->chat_id = $chat->id;
        $tChat->ctime = time();
        $tChat->phone = $phone;
        $tChat->tphone_id = $tPhone->id;
        $tChat->utime = time();
        $tChat->saveThis();
    }

    /**
     * @param object $chat
     * @param string $msg
     * @param int $user
     * @param string|null $ns
     * @param int $minusTime
     * @return erLhcoreClassModelmsg
     * @throws ezcPersistentObjectException
     */
    private static function newModelMsg($chat, $msg, $user, $ns = null, $minusTime = 0) {
        $model = new erLhcoreClassModelmsg;
        $model->chat_id = $chat->id;
        $model->msg = trim($msg);
        $model->name_support = (string)$ns;
        $model->time = time() + ((null === $ns ? 0 : 5) - $minusTime);
        $model->user_id = $user;

        erLhcoreClassChat::getSession()->save($model);

        return $model;
    }

    public function processCallback() {
        if ($this->settings['debug']) erLhcoreClassLog::write(var_dump($_POST));

        $res = erLhcoreClassChatEventDispatcher::getInstance()
            ->dispatch('sms77.process_callback', $_POST);

        if ($res !== false
            && $res['status'] === erLhcoreClassChatEventDispatcher::STOP_WORKFLOW)
            throw new Exception('Module disabled for user');

        if (!isset($_POST['system'])) throw new Exception('Invalid recipient');

        $sms77Phone = erLhcoreClassModelSms77Phone::findOne(
            ['filter' => ['phone' => $_POST['system']]]);

        if (!$sms77Phone) $sms77Phone = erLhcoreClassModelSms77Phone::findOne(
            ['filter' => ['phone' => str_replace('+', '', $_POST['system'])]]);

        if (!$sms77Phone) {
            $sms77Phone = erLhcoreClassModelSms77Phone::findOne(['customfilter' =>
                ['concat(`base_phone`, `phone`) = '
                    . ezcDbInstance::get()->quote($_POST['system'])]]);

            // replace from all passed variables + as in Sms77 this number was without + in front
            if ($sms77Phone) {
                $_POST['sender'] =
                    str_replace($sms77Phone->base_phone, '', $_POST['sender']);
                $_POST['system'] =
                    str_replace($sms77Phone->base_phone, '', $_POST['system']);
            }
        }

        if (($this->settings['ahenviroment'] === false && $sms77Phone === false)
            || ($this->settings['ahenviroment'] === true
                && !array_key_exists($_POST['system'],
                    $this->ahinstance->phone_number_departments)))
            throw new Exception('Invalid recipient');

        $tChat = erLhcoreClassModelSms77Chat::findOne([
            'filter' => ['phone' => $_POST['sender'], 'tphone_id' => $sms77Phone->id],
            'filtergt' => ['utime' => time() - $sms77Phone->chat_timeout],
        ]);

        if ($tChat !== false && ($chat = $tChat->chat) !== false) {
            $renotify = false;

            if ($chat instanceof erLhcoreClassModelChat
                && (int)($chat->status === erLhcoreClassModelChat::STATUS_CLOSED_CHAT)) { // fix https://github.com/LiveHelperChat/fbmessenger/issues/1
                $chat->pnd_time = time();
                $chat->status = erLhcoreClassModelChat::STATUS_PENDING_CHAT;
                $chat->status_sub_sub = 2; // used to indicate that we have to show notification for this chat if it appears on list
                $chat->user_id = 0;
                $renotify = true;
            }

            $msg = self::newModelMsg($chat, $_POST['text'], 0);

            if ($chat->auto_responder === false) { // create auto responder if there is none
                $responder = erLhAbstractModelAutoResponder::processAutoResponder($chat);

                if ($responder instanceof erLhAbstractModelAutoResponder) {
                    $responderChat = self::newAutoResponderChat($chat, $responder);
                    $chat->auto_responder_id = $responderChat->id;
                    $chat->auto_responder = $responderChat;
                }
            }

            $chatVars = $chat->chat_variables_array;

            if ($chat->auto_responder !== false) { // Auto responder if department is offline
                $responder = $chat->auto_responder->auto_responder;

                if (is_object($responder) && $responder->offline_message !== ''
                    && !self::isChatOnline($chat)) {
                    if (!isset($chatVars['sms77_chat_timeout'])
                        || $chatVars['sms77_chat_timeout']
                        < time() - (int)$sms77Phone->responder_timeout) {
                        $chatVars['sms77_chat_timeout'] = time();
                        $chat->chat_variables_array = $chatVars;
                        $chat->chat_variables = json_encode($chatVars);

                        $msgResponder = self::newModelMsg(
                            $chat, $responder->offline_message, -2, $responder->operator !== ''
                            ? $responder->operator
                            : erTranslationClassLhTranslation::getInstance()
                                ->getTranslation('chat/startchat', 'Live Support'));
                        erLhcoreClassChat::getSession()->save($msgResponder);

                        if ($chat->last_msg_id < $msgResponder->id)
                            $chat->last_msg_id = $msgResponder->id;
                        $this->sendSMSUser(['chat' => $chat, 'msg' => $msgResponder]);
                    }
                }
            }

            $db = ezcDbInstance::get(); // Update related chat attributes
            $db->beginTransaction();

            $stmt = $db->prepare('UPDATE lh_chat SET pnd_time = :pnd_time, chat_variables = :chat_variables, status = :status, user_id = :user_id, status_sub_sub = :status_sub_sub, last_user_msg_time = :last_user_msg_time, last_msg_id = :last_msg_id, has_unread_messages = 1 WHERE id = :id');
            $stmt->bindValue(':id', $chat->id, PDO::PARAM_INT);
            $stmt->bindValue(':chat_variables', $chat->chat_variables);
            $stmt->bindValue(':last_msg_id', $chat->last_msg_id < $msg->id
                ? $msg->id : $chat->last_msg_id, PDO::PARAM_INT);
            $stmt->bindValue(':last_user_msg_time', $msg->time, PDO::PARAM_INT);
            $stmt->bindValue(':pnd_time', $chat->pnd_time, PDO::PARAM_INT);
            $stmt->bindValue(':status', $chat->status, PDO::PARAM_INT);
            $stmt->bindValue(':status_sub_sub', $chat->status_sub_sub, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $chat->user_id, PDO::PARAM_INT);
            $stmt->execute();

            $tChat->utime = time();
            $tChat->saveThis();

            $db->commit();

            if ($renotify) erLhcoreClassChatValidator::setBot($chat, ['msg' => $msg]);
            $this->sendBotResponse($chat, $msg, $renotify ? ['init' => true] : []);

            if ((int)$chat->has_unread_messages === 1
                && $chat->last_user_msg_time < (time() - 5))
                erLhcoreClassChatEventDispatcher::getInstance()
                    ->dispatch('chat.unread_chat', ['chat' => &$chat]); // Standard event on unread chat messages

            // Dispatch same event as we were using desktop client; it force admins and users to resync chat for new messages
            // It allows NodeJS users to know about new message. In this particular case it's admin users
            // If operator has opened chat instantly sync
            erLhcoreClassChatEventDispatcher::getInstance()->dispatch(
                'chat.messages_added_passive', ['chat' => &$chat, 'msg' => $msg]);

            erLhcoreClassChatEventDispatcher::getInstance()->dispatch(
                'chat.nodjshelper_notify_delay', ['chat' => &$chat, 'msg' => $msg]); // If operator has closed a chat we need force back office sync

            erLhcoreClassChatEventDispatcher::getInstance()->dispatch(
                'sms77.sms_received', ['chat' => &$chat, 'msg' => $msg]); // general module signal that it has received an sms

            if ($renotify === true) erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('chat.restart_chat', ['chat' => &$chat, 'msg' => $msg]); // general module signal that it has received an sms
        } else {
            $chat = new erLhcoreClassModelChat;
            $chat->phone = $_POST['sender'];

            if ($this->settings['ahenviroment'] == true) {
                if ($this->ahinstance->phone_number_departments[$_POST['system']] > 0) // Perhaps phone number has assigned department directly
                    try {
                        $department = erLhcoreClassModelDepartament::fetch(
                            $this->ahinstance->phone_number_departments[$_POST['system']]);
                        $chat->dep_id = $department->id;
                    } catch (Exception $e) {
                    }
                else // Fallback to default if not defined
                    if ($this->ahinstance->phone_default_department > 0)
                        try {
                            $department = erLhcoreClassModelDepartament::fetch(
                                $this->ahinstance->phone_default_department);
                            $chat->dep_id = $department->id;
                        } catch (Exception $e) {
                        }
            }

            if ((int)$chat->dep_id === 0) {
                if ($this->settings['ahenviroment'] === false
                    && $sms77Phone->dep_id > 0) {
                    $depId = $sms77Phone->dep_id;
                    $department = erLhcoreClassModelDepartament::fetch($depId);

                    if ($department instanceof erLhcoreClassModelDepartament) {
                        $chat->dep_id = $department->id;
                        $chat->priority = $department->priority;
                    } else throw new Exception(
                        "Could not find department by phone number - $depId");

                } else {
                    $departments = erLhcoreClassModelDepartament::getList(
                        ['filter' => ['disabled' => 0], 'limit' => 1]);

                    if (!empty($departments)) {
                        $department = array_shift($departments);
                        $chat->dep_id = $department->id;
                        $chat->priority = $department->priority;
                    } else throw new Exception('Could not detect default department');
                }
            }

            $chat->hash = erLhcoreClassChat::generateHash();
            $chat->nick = erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('sms77/sms', 'SMS') . ' ' . $chat->phone;
            $chat->pnd_time = time();
            $chat->referrer = '';
            $chat->session_referrer = '';
            $chat->status = 0;
            $chat->time = time();
            $chatVars = [
                'sms77_from' => $_POST['system'],
                'sms77_phone_id' => $sms77Phone->id,
                'sms77_sms_chat' => true,
            ];
            $chat->chat_variables = json_encode($chatVars);
            $chat->saveThis();

            $msg = self::newModelMsg($chat, $_POST['text'], 0);
            erLhcoreClassChatValidator::setBot($chat, ['msg' => $msg]);
            $this->sendBotResponse($chat, $msg, ['init' => true]);

            $chat->last_msg_id = $msg->id;
            $chat->last_user_msg_time = $msg->time;

            $responder = erLhAbstractModelAutoResponder::processAutoResponder($chat);
            if ($responder instanceof erLhAbstractModelAutoResponder) {
                $chat->auto_responder_id = self::newAutoResponderChat($chat, $responder)->id;

                if ($chat->status !== erLhcoreClassModelChat::STATUS_BOT_CHAT
                    && $responder->offline_message !== ''
                    && !self::isChatOnline($chat)) {
                    $msg = self::newModelMsg($chat, $responder->offline_message, -2,
                        $responder->operator !== '' ? $responder->operator
                            : erTranslationClassLhTranslation::getInstance()
                            ->getTranslation('chat/startchat', 'Live Support'));
                    $messageResponder = $msg;

                    if ($chat->last_msg_id < $msg->id) $chat->last_msg_id = $msg->id;

                    $chatVars['sms77_chat_timeout'] = time();
                    $chat->chat_variables = json_encode($chatVars);
                    $chat->chat_variables_array = $chatVars;
                }
            }

            $chat->saveThis();

            self::newChatModel($chat, $_POST['sender'], $sms77Phone);

            if (isset($messageResponder)) // auto responder has something to send to visitor
                $this->sendSMSUser(['chat' => $chat, 'msg' => $messageResponder]);

            erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('chat.chat_started', ['chat' => &$chat, 'msg' => $msg]); // execute standard callback as chat was started

            erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('sms77.sms_received', ['chat' => &$chat, 'msg' => $msg]); // general module signal that it has received an sms
        }
    }

    private static function newAutoResponderChat($chat, $responder) {
        $responderChat = new erLhAbstractModelAutoResponderChat;
        $responderChat->auto_responder_id = $responder->id;
        $responderChat->chat_id = $chat->id;
        $responderChat->wait_timeout_send = 1 - $responder->repeat_number;
        $responderChat->saveThis();

        return $responderChat;
    }

    private static function isChatOnline($chat) {
        return erLhcoreClassChat::isOnline($chat->dep_id, false, [
            'exclude_bot' => true,
            'ignore_user_status' => (int)erLhcoreClassModelChatConfig::fetch(
                'ignore_user_status')->current_value,
            'online_timeout' => (int)erLhcoreClassModelChatConfig::fetch(
                'sync_sound_settings')->data['online_timeout'],
        ]);
    }

    /**
     * @desc processes sms callback and stores a new chat or appends a message to existing
     */
    public function sendSMSUser(array $params) {
        if ($this->settings['debug']) erLhcoreClassLog::write(var_dump($params));

        $chatVars = $params['chat']->chat_variables_array;

        if (!isset($chatVars['sms77_sms_chat'])
            || (int)$chatVars['sms77_sms_chat'] !== 1) return;

        try { // It's SMS chat we need to send a message
            $res = erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('sms77.send_sms_user', $params);

            if ($res !== false && $res['status']
                === erLhcoreClassChatEventDispatcher::STOP_WORKFLOW) // Check is module disabled
                throw new Exception(erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('sms77/sms', 'Module is disabled for you!'));

            if ($params['msg']->msg === '')
                throw new Exception(erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('sms77/sms', 'Please enter a message!'));

            if ($this->settings['ahenviroment'] === true
                && (bool)$this->ahinstance->hard_limit_in_effect === true)
                throw new Exception(erTranslationClassLhTranslation::getInstance()
                    ->getTranslation('sms77/sms', 'SMS could not be send because
                     you have reached your SMS hard limit!'));

            $phone = isset($chatVars['sms77_phone_id'])
            && is_numeric($chatVars['sms77_phone_id']) ?
                erLhcoreClassModelSms77Phone::fetch($chatVars['sms77_phone_id'])
                : erLhcoreClassModelSms77Phone::findOne(
                    ['filter' => ['phone' => $chatVars['sms77_from']]]);

            $signatureText = ''; // Prepend Signature if Telegram extension is used

            $statusSignature = erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('telegram.get_signature',
                    ['user_id' => erLhcoreClassUser::instance()->getUserID()]);

            if ($statusSignature !== false)
                $signatureText = $statusSignature['signature'];

            $recipientPhone = str_replace($phone->base_phone, '',
                $params['chat']->phone);

            if (strpos($recipientPhone, '+') === false)
                $recipientPhone = $phone->base_phone . $recipientPhone;

            $paramsSend = [
                'ApiKeySend' => $phone->api_key,
                'from' => $phone->base_phone . $phone->phone,
                'recipient' => $recipientPhone,
                'text' => $params['msg']->msg . $signatureText,
            ];

            if (isset($chatVars['sms77_from'])
                && $chatVars['sms77_from'] !== '')
                $paramsSend['from'] = $phone->base_phone
                    . str_replace($phone->base_phone, '', $chatVars['sms77_from']);

            if ($this->settings['ahenviroment'] === true) {
                $paramsSend['from'] = isset($chatVars['sms77_from'])
                    ? $phone->base_phone . // Use same sender as recipient
                    str_replace($phone->base_phone, '', $chatVars['sms77_from'])
                    : $this->ahinstance->phone_number_first;

                $paramsSend['password'] =
                    $this->ahinstance->getPhoneAttribute('ApiKeySend');
            }

            $this->initClient($phone->api_key)->sms($paramsSend['recipient'],
                $paramsSend['text'], ['from' => $paramsSend['from']]);

            if (!isset($chatVars['sms77_sms_chat_send']))
                $chatVars['sms77_sms_chat_send'] = 0;

            $newMessagesCount = ceil(mb_strlen($params['msg']->msg) / 160);
            $chatVars['sms77_sms_chat_send'] += $newMessagesCount;

            $db = ezcDbInstance::get();
            $db->beginTransaction();
            $stmt = $db->prepare('UPDATE lh_chat SET chat_variables = :chat_variables,operation_admin = :operation_admin WHERE id = :id');
            $stmt->bindValue(':id', $params['chat']->id, PDO::PARAM_INT);
            $stmt->bindValue(':chat_variables', json_encode($chatVars));
            $stmt->bindValue(':operation_admin',
                "lhinst.updateVoteStatus(" . $params['chat']->id . ")");
            $stmt->execute();
            $db->commit();

            if ($this->settings['ahenviroment'] === true)
                $this->ahinstance->addSMSMessageSend($newMessagesCount);

            erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('sms77.sms_send_to_user', ['chat' => & $params['chat']]); // event that it has send an sms

            erLhcoreClassChatEventDispatcher::getInstance()
                ->dispatch('chat.nodjshelper_notify_delay', ['chat' => &$params['chat']]);  // force back office sync if operator has closed a chat
        } catch (Exception $e) {
            $msg = self::newModelMsg($params['chat'], $e->getMessage(), -1);

            $db = ezcDbInstance::get();
            $db->beginTransaction();
            $stmt = $db->prepare('UPDATE lh_chat SET last_user_msg_time = :last_user_msg_time, last_msg_id = :last_msg_id WHERE id = :id');
            $stmt->bindValue(':id', $params['chat']->id, PDO::PARAM_INT);
            $stmt->bindValue(':last_user_msg_time', $msg->time);
            $stmt->bindValue(':last_msg_id', $msg->id);
            $stmt->execute();
            $db->commit();

            if ($this->settings['debug'] === true)
                erLhcoreClassLog::write(var_dump($e));
        }
    }

    public function sendBotResponse($chat, $msg, $params = []) {
        if (!($chat->gbot_id > 0 && (!isset($chat->chat_variables_array['gbot_disabled'])
                || (int)$chat->chat_variables_array['gbot_disabled'] === 0)))
            return;

        $chat->refreshThis();

        if (!isset($params['init']) || (bool)$params['init'] === false)
            erLhcoreClassGenericBotWorkflow::userMessageAdded($chat, $msg);

        foreach (erLhcoreClassModelmsg::getList([ // Find new messages
            'filter' => ['chat_id' => $chat->id],
            'filtergt' => ['id' => $msg->id],
            'user_id' => -2,
        ]) as $botMessage) erLhcoreClassChatEventDispatcher::getInstance()
            ->dispatch('chat.web_add_msg_admin', [
                'chat' => &$chat,
                'msg' => $botMessage,
            ]);
    }
}


