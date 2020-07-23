<?php

use GuzzleHttp\Client;

function getUrl($text)
{
    define ('KEY', 'AIzaSyAca6wkF2WEjAhKUxWG4j-puh4MixVnd9w');
    define ('CX', '007381751698148361103:jv6cuoyl1lu');

    // Формируем запрос
    $q = http_build_query(array(
        'key' => KEY,
        'cx'  => CX,
        'searchType' => 'image',
        'imgSize' => 'xxlarge',
        'imgType' => 'photo',
        'sort' => 'date:r:'.date('Ymd').':',
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
    $url = $results['items'][0]['link'];

    return $url;
}

?>