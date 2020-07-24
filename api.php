<?php

use GuzzleHttp\Client;

function getPhotoUrlFromQuery($city)
{
    $maxNumberOfPhotos = 10;
    define ('KEY', 'AIzaSyAca6wkF2WEjAhKUxWG4j-puh4MixVnd9w');
    define ('CX', '007381751698148361103:jv6cuoyl1lu');

    // Формируем запрос
    $query = http_build_query(array(
        'key' => KEY,
        'cx'  => CX,
        'searchType' => 'image',
        'excludeTerms' => 'мебель одежда еда',
        'sort' => 'date:r:'.date('Ymd').':',
        'num' => $maxNumberOfPhotos,
        'q' => $city // запрос для поиска
    ));

    // Инициализация клиента
    $client = new Client(array(
        'base_uri' => 'https://www.googleapis.com/customsearch/v1',
        'query'    => $query,
        'timeout'  => 60,
        'debug'    => false,
        'headers'  => array(
            'Accept' => 'application/json'
        ),
    ));

    // Отправка запроса и получение результатов поиска
    $response = $client->request('GET');
    $results = json_decode($response->getBody()->getContents(), true);
    $url = $results['items'][rand(0, $maxNumberOfPhotos-1)]['link'];

    return $url;
}

?>