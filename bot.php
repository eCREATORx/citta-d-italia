<?php

include('vendor/autoload.php');
require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use GuzzleHttp\Client;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$result = $telegram -> getWebhookUpdates();

$key = 'AIzaSyAca6wkF2WEjAhKUxWG4j-puh4MixVnd9w';
$cx = '007381751698148361103:jv6cuoyl1lu';

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');
$db->autoReconnect = false;

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Бергамо", "Венеция"], ["Милан", "Неаполь"], ["Палермо", "Рим"], ["Турин", "Флоренция"]]; //Клавиатура

if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота! Выберите город для подписки.";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }elseif ($text == "/help") {
        $reply = "Информация с помощью.";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif (($text == "Бергамо") || ($text == "Венеция") || ($text == "Милан") || ($text == "Палермо") || ($text == "Рим") || ($text == "Флоренция")) {
        // Формируем запрос
        $q = http_build_query(array(
            'key' => $key,
            'cx'  => $cx,
            'searchType' => 'image',
            'imgSize' => 'xxlarge',
            'imgType' => 'photo',
            'num' => 1,
            'q' => $text // запрос для поиска
        ));

        // Инициализация клиента
        $client = new Client(array(
            'base_uri' => 'https://www.googleapis.com/customsearch/v1',
            'query'    => $q,
            'timeout'  => 60,
            'debug'    => false,
            'headers'  => array(
                'Accept' => 'application/json'
            ),
        ));

        // Отправка запроса и получение результатов поиска
        $response = $client->request('GET');
        $results = json_decode($response->getBody()->getContents(), true);
        $url = $results["items"][0]["link"];

        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Фото по запросу ".$text."." ]);

        // Добавление в БД
        $reply = "Желаете оформить подписку, чтобы ежедневно получать новые фото?";

        $update = $this->telegram->getWebhookUpdate();

        if ($update->isType('callback_query')) {

            $this->telegram->sendMessage([
                'chat_id' => $update->callbackQuery->from->id,
                'text' => $update->callbackQuery->data
            ]);
        } else {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton([
                        'text' => 'Test Btn',
                        'callback_data' => 'callback_from_testbtn'
                    ])
                );

            $this->telegram->sendMessage([
                'chat_id' => $update->getMessage()->chat->id,
                'text' => 'Text with inline button',
                'reply_markup' => $keyboard
            ]);
        }  
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
?>