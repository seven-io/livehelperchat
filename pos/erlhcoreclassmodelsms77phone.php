<?php

$def = new ezcPersistentObjectDefinition;
$def->table = 'lhc_sms77_phone';
$def->class = 'erLhcoreClassModelSms77Phone';

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->generator =
    new ezcPersistentGeneratorDefinition('ezcPersistentNativeGenerator');
$def->idProperty->propertyName = 'id';

$def->properties['api_key'] = new ezcPersistentObjectProperty;
$def->properties['api_key']->columnName = 'api_key';
$def->properties['api_key']->propertyName = 'api_key';
$def->properties['api_key']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['base_phone'] = new ezcPersistentObjectProperty;
$def->properties['base_phone']->columnName = 'base_phone';
$def->properties['base_phone']->propertyName = 'base_phone';
$def->properties['base_phone']->propertyType =
    ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['chat_timeout'] = new ezcPersistentObjectProperty;
$def->properties['chat_timeout']->columnName = 'chat_timeout';
$def->properties['chat_timeout']->propertyName = 'chat_timeout';
$def->properties['chat_timeout']->propertyType =
    ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['dep_id'] = new ezcPersistentObjectProperty;
$def->properties['dep_id']->columnName = 'dep_id';
$def->properties['dep_id']->propertyName = 'dep_id';
$def->properties['dep_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['from'] = new ezcPersistentObjectProperty;
$def->properties['from']->columnName = 'from';
$def->properties['from']->propertyName = 'from';
$def->properties['from']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['phone'] = new ezcPersistentObjectProperty;
$def->properties['phone']->columnName = 'phone';
$def->properties['phone']->propertyName = 'phone';
$def->properties['phone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['responder_timeout'] = new ezcPersistentObjectProperty;
$def->properties['responder_timeout']->columnName = 'responder_timeout';
$def->properties['responder_timeout']->propertyName = 'responder_timeout';
$def->properties['responder_timeout']->propertyType =
    ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;
