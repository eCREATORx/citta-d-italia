<?php

include('vendor/autoload.php');
require_once('api.php');
require_once('database.php');
use Telegram\Bot\Api;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$result = $telegram->getWebhookUpdates();

$text = $result['message']['text']; // Текст сообщения
$chatId = $result['message']['chat']['id']; // Уникальный идентификатор пользователя

$cityList = ['Бергамо', 'Венеция', 'Милан', 'Неаполь', 'Палермо', 'Рим', 'Турин', 'Флоренция'];
$cityChunks = array_chunk($cityList, 2);
$keyboard = $cityChunks; //Клавиатура

if($text){
    if ($text === '/start') {
        $reply = 'Добро пожаловать в бота! Выберите город для подписки.';
        $replyMarkup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $replyMarkup ]);
    }elseif (in_array($text, $cityList, true)) {
        // Получение ссылки и отправка фото
        $url = getPhotoUrlFromQuery($text);
        $telegram->sendPhoto([ 'chat_id' => $chatId, 'photo' => $url, 'caption' => 'Фото по запросу '.$text.'.']);

        $reply = addToDatabase($chatId, $text);
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply ]);
    }else{
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Ваш запрос некорректен или подписка на данный город в данный момент невозможна.' ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Отправьте текстовое сообщение.' ]);
}

?>