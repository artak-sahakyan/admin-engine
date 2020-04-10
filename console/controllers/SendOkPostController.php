<?php
namespace console\controllers;

use Yii;
use common\models\{ Article, ArticleSocial };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use VK\Client\VKApiClient;
use common\helpers\FilesHelper;
use yii\helpers\Url;

//https://apiok.ru/dev/methods/rest/mediatopic/mediatopic.post
class SendOkPostController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex($article_id = null)
    {
        $this->startCommand();

        if(!$article_id) {
            $article = Article::find()
                ->joinWith('articleSocial')
                ->where([
                    'or', 
                    ['sended_ok' => null],
                    ['sended_ok' => 0]
                ])
                ->orderBy('id')
                ->limit(1)
                ->one();
        } else {
            $article = Article::findByKey($article_id);
        }

        if(!$article) {
            $this->console->output('Нет статей для отправки');
        } else {
            $this->console->output('Отправка статьи ' . $article->id . ' в OK');

            $sendStatus = self::processArticle($article);

            if ($sendStatus) {
                $this->console->output('Статья отправлена');
            } else {
                $this->console->output('Ошибка отправки статьи');
            }
        }

        $this->stopCommand();
        return ExitCode::OK;
    }

    public function processArticle($article) {

        $configs = Yii::$app->params['okru'];

        if(empty($configs['access_token']) || empty($configs['group_id'])) {
            $this->console->output('Не установлен access_token или group_id');
            return false;
        }

        $params = array(
            "application_key"   => $configs['public_key'],
            "method"            => "photosV2.getUploadUrl",
            "count"             => 1, 
            "gid"               => $configs['group_id'],
            "format"            => "json"
        );

        $sig = md5( $this->arInStr($params) . md5("{$configs['access_token']}{$configs['private_key']}") );

        $params['access_token'] = $configs['access_token'];
        $params['sig']          = $sig;

        $step1 = json_decode($this->getUrl("https://api.ok.ru/fb.do", "POST", $params), true);
        $attachment = '{
                            "media": [
                                {
                                    "type": "link",
                                    "url": "' . Url::base(true) . $article->getUrl().'"
                                },
                                {
                                    "type": "text",
                                    "text": "'.$article->title.'"
                                }
                            ]
                        }';

        $params = array(
            "application_key"   =>  $configs['public_key'],
            "method"            =>  "mediatopic.post",
            "gid"               =>  $configs['group_id'],
            "type"              =>  "GROUP_THEME",
            "attachment"        =>  $attachment,
            "format"            =>  "json",
        );

        $sig = md5( $this->arInStr($params) . md5("{$configs['access_token']}{$configs['private_key']}") );

        $params['access_token'] = $configs['access_token'];
        $params['sig']          = $sig;

        $response = json_decode( $this->getUrl("https://api.ok.ru/fb.do", "POST", $params, 30, false, false ), true);

        $this->console->endProgress();

        $articleSocial = $article->articleSocial;
        if(!$articleSocial) {
            $articleSocial = new ArticleSocial();
            $articleSocial->article_id = $article->id;
            $articleSocial->save();
        }

        $articleSocial->sended_ok = 1;
        $articleSocial->save();
    
        return $articleSocial->sended_ok;
    }

    private function getUrl($url, $type = "GET", $params = array(), $timeout = 30, $image = false, $decode = true)
    {
        if ($ch = curl_init())
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);

            if ($type == "POST")
            {
                curl_setopt($ch, CURLOPT_POST, true);

                if ($image) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                }
      
                elseif($decode) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
                }

                else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $data = curl_exec($ch);

            curl_close($ch);

            if (isset($data['error_code']) && $data['error_code'] == 5000) {
                $data = $this->getUrl($url, $type, $params, $timeout, $image, $decode);
            }

            return $data;

        }
        else {
            return "{}";
        }
    }

    private function arInStr($array)
    {
        ksort($array);

        $string = "";

        foreach($array as $key => $val) {
            if (is_array($val)) {
                $string .= $key."=".self::arInStr($val);
            } else {
                $string .= $key."=".$val;
            }
        }

        return $string;
    }
}
