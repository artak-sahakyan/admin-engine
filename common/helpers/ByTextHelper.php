<?php
namespace common\helpers;

use yii\httpclient\Client;

class ByTextHelper
{
    const ENDPOINT = 'http://bytext.ru/api.php';

    public static function anchors($articleId)
    {
        $response = self::sendRequest('getAnkors', ['task_id' => $articleId]);

        if($response['result'] == 'success') {
            return $response['ankorsTaskCreatorWordstat'];
        }
        if ($response['result'] == 'error') {
            return $response['message'];
        }
        return $response;
    }

    /**
    * @param string $action
    * @param array $data
    * @return array|mixed
    * @throws \Exception
    */
    public static function sendRequest($action, array $data = [])
    {
        $data['action'] = $action;

        $url = self::ENDPOINT . '?' . http_build_query($data);

        $httpClient = new Client();
        $request = $httpClient->get($url)->send();

        if($request->statusCode == 200) {
            return $request->getData();
        }

        return 'Нет ответа от byText';
    }
}
