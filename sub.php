<?php

include('vendor/autoload.php');
require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');
use Telegram\Bot\Api;
use GuzzleHttp\Client;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$result = $telegram -> getWebhookUpdates();

$key = 'AIzaSyAca6wkF2WEjAhKUxWG4j-puh4MixVnd9w';
$cx = '007381751698148361103:jv6cuoyl1lu';

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');
$db->autoReconnect = false;

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя

$chat_ids = $db->getValue("subscriptions", "chat_id", null);
$chat_ids = implode($chat_ids)

$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $chat_ids ]);
?>