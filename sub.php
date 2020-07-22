<?php

require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');
require_once('bot.php');

global $db, $telegram;

$users = $db->getValue("subscriptions", "chat_id", null);
foreach ($users as $chat_id) {
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $chat_id ]);
}

?>