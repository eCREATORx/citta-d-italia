<?php

include('vendor/autoload.php');
use Telegram\Bot\Api;
use GuzzleHttp\Client;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$key = 'AIzaSyAca6wkF2WEjAhKUxWG4j-puh4MixVnd9w';
$cx = '007381751698148361103:jv6cuoyl1lu';
$result = $telegram -> getWebhookUpdates();

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Венеция"],["Милан"],["Рим"],["Флоренция"]]; //Клавиатура

if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота! Выберите город для подписки";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }elseif ($text == "/help") {
        $reply = "Информация с помощью.";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "Венеция" || "Милан" || "Рим" || "Флоренция") {
        // Формируем запрос
        $q = http_build_query(array(
            'key' => $key,
            'cx'  => $cx,
            'searchType' => 'image',
            'imgSize' => 'xxlarge',
            'imgType' => 'photo',
            'sort' => 'date:d:s',
            'num' => 1,
            'q' => $text // запрос для поиска
        ));

        // Инициализация клиента
        $client = new Client(array(
            'base_uri' => 'https://www.googleapis.com/customsearch/v1',
            'query'    => $q,
            'timeout'  => 3.0,
            'debug'    => false,
            'headers'  => array(
                'Accept' => 'application/json'
            ),
        ));

        // отправка запроса и получение результатов поиска
        $response = $client->request('GET');
        $results = json_decode($response->getBody()->getContents(), true);
        $results = '"'.$results["items"][0]["link"].'"';

        $url = $results;
        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Фотография города ".$text ]);
    }else{
        $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
?>