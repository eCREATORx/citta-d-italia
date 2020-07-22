<?php

use Telegram\Bot\Api;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');

$telegram->sendMessage([ 'chat_id' => '981289062', 'text' => 'test schedule' ]);

?>