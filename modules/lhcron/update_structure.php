<?php
/** php cron.php -s site_admin -e sms77 -c cron/update_structure */

$structure = file_get_contents('extension/sms77/doc/structure.json');
$structure = json_decode($structure, true);
$queries = [];

foreach (erLhcoreClassUpdate::getTablesStatus($structure) as $status)
    $queries = array_merge($queries, $status['queries']);

if (empty($queries)) echo 'No queries to execute found' . PHP_EOL;
else {
    echo 'The following queries will be executed' . PHP_EOL
        . 'You have 10 seconds to stop executing these queries' . PHP_EOL
        . implode(PHP_EOL, $queries) . PHP_EOL;

    sleep(10);
    erLhcoreClassUpdate::doTablesUpdate($structure);

    echo 'Tables were updated';
}
