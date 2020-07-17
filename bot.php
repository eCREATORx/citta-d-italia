<?php

include('vendor/autoload.php');
use Telegram\Bot\Api;

$telegram = new Api('1016659380:AAFbs7DXrHGnd87S-4_-EF-7bXFeSmdV9bg');
$result = $telegram -> getWebhookUpdates();

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Венеция"],["Милан"],["Рим"],["Флоренция"]]; //Клавиатура

if($text){
    if ($text == "/start") {
        $reply = "Добро пожаловать в бота! Выберите интересующий город для подписки";
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }elseif ($text == "/help") {
        $reply = "Информация с помощью.";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "Венеция") {
        $url = "https://www.cruisetradenews.com/wp-content/uploads/2019/08/venice-canal-1.jpg";
        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Вененция" ]);
    }elseif ($text == "Милан") {
        $url = "https://34travel.me/media/upload/images/2016/march/milan_guide/new/IMG_6944.jpg";
        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Милан" ]);
    }elseif ($text == "Рим") {
        $url = "https://www.telegraph.co.uk/content/dam/Travel/Destinations/Europe/Italy/Rome/rome-sunset-tiber-river-overview-city-guide.jpg";
        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Рим" ]);
    }elseif ($text == "Флоренция") {
        $url = "https://specials-images.forbesimg.com/imageserve/852835136/960x0.jpg?fit=scale";
        $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Флоренция" ]);
    }else{
        $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
}else{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}
?>