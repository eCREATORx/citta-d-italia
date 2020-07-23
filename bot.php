<?php

include('vendor/autoload.php');
require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');
require_once('api.php');
use Telegram\Bot\Api;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$result = $telegram->getWebhookUpdates();

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');

$text = $result['message']['text']; // Текст сообщения
$chatId = $result['message']['chat']['id']; // Уникальный идентификатор пользователя
$keyboard = [['Бергамо', 'Венеция'], ['Милан', 'Неаполь'], ['Палермо', 'Рим'], ['Турин', 'Флоренция']]; //Клавиатура

if($text){
    if ($text == '/start') {
        $reply = 'Добро пожаловать в бота! Выберите город для подписки.';
        $replyMarkup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $replyMarkup ]);
    }elseif (($text == 'Бергамо') || ($text == 'Венеция') || ($text == 'Милан') || ($text == 'Неаполь') || ($text == 'Палермо') || ($text == 'Рим') || ($text == 'Турин')|| ($text == 'Флоренция')) {
        // Получение ссылки и отправка фото
        $url = getUrl($text);
        $telegram->sendPhoto([ 'chat_id' => $chatId, 'photo' => $url, 'caption' => 'Фото по запросу '.$text.'.']);

        // Проверка наличия пользователя в БД
        $db->where('chat_id', $chatId);
        if ($db->has('subscriptions')) {
            // Обновление данных
            $data = array('chat_id' => $chatId,
               'city' => $text
            );
            $db->where('chat_id', $chatId);
            $sub = $db->update('subscriptions', $data);
            if ($sub) {
                $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Подписка на город '.$text.' обновлена.' ]);
            }
        }else{
            // Добавление данных
            $data = array('chat_id' => $chatId,
               'city' => $text
            );
            $sub = $db->insert('subscriptions', $data);
            if ($sub) {
            $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Подписка на город '.$text.' оформлена.' ]);
            } 
        }   
    }else{
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Ваш запрос некорректен или подписка на данный город в данный момент невозможна.' ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => 'Отправьте текстовое сообщение.' ]);
}

?>