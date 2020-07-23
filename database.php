<?php

require_once('vendor/thingengineer/mysqli-database-class/MysqliDb.php');
require_once('vendor/thingengineer/mysqli-database-class/dbObject.php');

$db = new MysqliDb('us-cdbr-east-02.cleardb.com', 'b869ac278f05ad', '1dfb91f0', 'heroku_f954956b083bef4');

function addToDatabase($chatId, $city)
{
	$data = array('chat_id' => $chatId,
        'city' => $city
    );
	// Проверка наличия пользователя в БД
    $db->where('chat_id', $chatId);
    if ($db->has('subscriptions')) {
        // Обновление данных
        $db->where('chat_id', $chatId);
        $sub = $db->update('subscriptions', $data);
        if ($sub) {
            $responseFromDatabase = 'Подписка на город '.$city.' обновлена.';
        }
    }else{
        // Добавление данных
        $sub = $db->insert('subscriptions', $data);
        if ($sub) {
        	$responseFromDatabase = 'Подписка на город '.$city.' оформлена.';
        } 
    }

    return $responseFromDatabase;
}