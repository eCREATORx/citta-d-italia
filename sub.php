<?php

include('vendor/autoload.php');
require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');
use Telegram\Bot\Api;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');

$users = $db->getValue("subscriptions", "chat_id", null);
foreach ($users as $chat_id)
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'test schedule' ]);

?>