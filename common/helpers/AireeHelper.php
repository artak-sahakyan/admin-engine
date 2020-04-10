<?php
namespace common\helpers;

use Yii;
use common\models\Article;
use yii\httpclient\Client;

class AireeHelper
{
    public static function clearCache($article_id)
    {
        $article = Article::findOne($article_id);
        $configs = Yii::$app->params['airee'];

        $domain = UrlHelper::getDomain();

        if(empty($configs['key'])) {
            return false;
        }

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $pages = [
            $article->getUrl(),
            $article->previewPath . $article->publicId . '/', // - img catalog
        ];
        $postData = [
            'pages' => implode("\n", $pages)
        ];
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_CURL)
            ->setMethod('POST')
            ->setUrl('https://airee.cloud/my/site/api/?action=flush.custom&domain=' . $domain . '&key=' . $configs['key'])
            ->setData($postData)
            ->setOptions([
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ])
            ->send();

        $responseJson = json_decode($response->content, true);
        if(!empty($responseJson['success'])) {
            $article->airee_clear_cache_date = strtotime(date('Y-m-d H:i'));
            $article->save();
        }

        return $response->content;
    }
}