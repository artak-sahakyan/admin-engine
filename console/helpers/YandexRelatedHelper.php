<?php


namespace console\helpers;

use Yii;
use common\helpers\FilesHelper;
use common\models\Article;
use common\models\ArticleRelatedYandex;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class YandexRelatedHelper
{

    CONST USER = 'limonbucks';
    CONST KEY = '03.239687484:ff83ae34536e95d5ddb98d5da69ba29c';

    public static function getAuthParams() {

        $configs = Yii::$app->params['yandex'];
        return [
            'user' => $configs['yandex_related_user'],
            'key' => $configs['yandex_related_key']
        ];
    }

    public static function savingIds($ids, $article)
    {

        $i = 1;
        echo "Saving in db\n";
        echo "=================\n";
        foreach ($ids as $id) {
            if($article->id == $id) {
                echo "Dont save self id\n";
                continue;
            }
            if($i == 21) {break;}
            echo $i++ .".".$article->id.'=>'.$id."\n";

            try {
                $related = new ArticleRelatedYandex();
                $related->article_id = $article->id;
                $related->related_article_id = $id;
                $related->save();
            } catch (\Exception $e) {
                echo "Dont saved $id\n";
            }
        }
        echo "=================\n";
        echo "end saving in db\n";
    }

    public static function checkResponseError($response, $id)
    {
        $error = $response['response']['error'];
        if(!empty($error) && is_string($error)) {
            $error = mb_convert_encoding($error, "UTF-8");
            echo "Error $error in article id={$id} \n";

            $path = \Yii::getAlias('@siteConsole')."/runtime/testing1.log";
            file_put_contents($path, "Error $error in article id={$id} \n");
        }

        echo "Not valid response\n";
        die('Ending script with error');
    }

    public static function sendRequest($article, $authParams = [])
    {

        if(!$article->main_query && $article->relatedYandexArticles->meta_title) {
            $article->main_query = $article->relatedYandexArticles->meta_title;
        }

        if(!$article->main_query) {
            echo "Dont see  main_query for article id=".$article->id."\n";
            return false;
        }

        $url = self::buildToYandexQuery($article->main_query, $authParams = []);

        $httpClient = new Client();
        $request = $httpClient->get($url)->send();

        if($request->statusCode == 200) {
            return $request->getData();
        }
        return false;
    }

    public static function buildToYandexQuery($mainQuery, $options = [])
    {

        $query = 'site:' . \Yii::$app->params['currentSiteHost'] .' '.$mainQuery;

        echo "Sending request to yandex\n";

        $configs = Yii::$app->params['yandex'];

        $user = $configs['yandex_related_user'];
        $key = $configs['yandex_related_key'];

        $params = [
            'user' => $user,
            'key' => $key,
            'query' => $query,
            'lr' => 225,
            'sortby' => 'rlv',
            'filter' => 'none',
            'groupby' => 'attr="".mode=flat.groups-on-page=40.docs-in-group=1',
        ];

        $buildQuery = http_build_query($params);
        return "https://yandex.ru/search/xml?".$buildQuery;
    }

    public static function getIdFromUrl($url)
    {
        $link = parse_url($url);
        $link = ltrim($link['path'], '/');

        if($id = intval($link)) {
            return $id;
        } else {
            echo "cant find id from ".$url."\n";
        }
        return false;
    }

    public static function relatedArticlesFromResponse($response, $id)
    {
        $ids = [];
        $j = 0;

        if ($results = ArrayHelper::getValue($response, 'response.results.grouping.group')) {
            echo "valid response\n";
            foreach ($results as $result) {
                if (!empty($result['doc']['url'])) {
                    if ($id = self::getIdFromUrl($result['doc']['url'])) {
                        if($j >= 21) {
                            // we need 20 id for save
                            break;
                        }
                        $ids[] = $id;
                        $j++;
                    }
                }
            }
            return $ids;
        }
        self::checkResponseError($response, $id);
    }


    public static function updateBigData($query)
    {
        /* @var $query ActiveQuery */
        foreach (self::getArticles($query) as $article) {

            if (!$article->main_query) continue;

            /* @var Article $article */
            $article->unlinkAll('relatedYandexArticles', true);
            sleep(1);
            $response = self::sendRequest($article, self::getAuthParams());

            echo "getting response for " . $article->id . "\nchecking valid for us\n";

            $ids = self::relatedArticlesFromResponse($response, $article->id);

            if ($ids) {
                self::savingIds($ids, $article);
            }
        }
    }

    private static function getArticles($query, $limit = null)
    {
        $perPage = 100;
        $page = 0;
        $i = 0;

        while ($articles = $query->orderBy('id')->limit($perPage)->offset($perPage * $page)->all()) {
            foreach ($articles as $articleRow) {

                if (isset($limit) && $i >= $limit) {
                    break 2;
                }

                yield $articleRow;

                $i++;
            }

            $page++;
        }
    }


    public static function updateArticle($id = 4)
    {
        $article = Article::findOne($id);

        if(!$article) die('Cant find Article');

        $article->unlinkAll('relatedYandexArticles', true);

        $response = self::sendRequest($article, self::getAuthParams());

        echo "getting response checking valid for us\n";

        $ids = self::relatedArticlesFromResponse($response, $article->id);

        if ($ids) {
            self::savingIds($ids, $article);
        }

        die("Success end test");
    }

}