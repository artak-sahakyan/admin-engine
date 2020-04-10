<?php

namespace common\helpers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use yii\httpclient\Exception;

/**
 * Yandex webmaster API
 * https://tech.yandex.ru/webmaster/doc/dg/concepts/About-docpage/
 */
class Api
{
    const API_URL = 'https://api.webmaster.yandex.net/v3';

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $hostId;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(Array $config, ClientInterface $httpClient = null)
    {
      //  $this->token = $config['token'];
        $this->token = $config['yandex_oauth_token'];
        $this->hostId = $config['host_id'];
        $this->userId = $config['user_id'];
        $this->httpClient = $httpClient ? $httpClient : new Client();
    }

    /**
     * @param $content string
     * @return object
     */
    public function addOriginalTexts($content)
    {
        $content = htmlspecialchars(strip_tags($content));

        $body = \GuzzleHttp\json_encode([
            'content' => $content,
        ]);

        $response = $this->send('POST', "/user/$this->userId/hosts/$this->hostId/original-texts/", [
            'body' => $body,
        ]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    public function getUserId()
    {
        $response = $this->httpClient->request('GET', "https://api.webmaster.yandex.net/v3/user/", $this->defaultOptions());

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    /**
     * @param $method
     * @param $url
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send($method, $url, array $options = [])
    {
        $options = array_merge_recursive($options, $this->defaultOptions());

        try {
            return $this->httpClient->request($method, self::API_URL . $url, $options);
        } catch (ClientException $e) {
            $jsonResponse = \GuzzleHttp\json_decode($e->getResponse()->getBody()->getContents());

            throw new Exception($jsonResponse->error_message, 0, $e);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    private function defaultOptions()
    {
        return [
            'headers' => [
                'Authorization' => 'OAuth ' . $this->token,
                'Content-type' => 'application/json',
            ],
        ];
    }
}