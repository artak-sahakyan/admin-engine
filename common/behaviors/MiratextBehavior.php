<?php
namespace common\behaviors;

use common\helpers\ArrayHelper;
use yii\base\Behavior;
use yii\httpclient\Client;
use common\models\NauseaOfArticle;

class MiratextBehavior extends Behavior
{

    private $content;

    const ENDPOINT = 'https://miratext.ru';

    public function updateMiratextData()
    {
        try {
            $statRecord = $this->owner->nauseaOfArticle ? $this->owner->nauseaOfArticle : new NauseaOfArticle();
            $statRecord->article_id = $this->owner->id;

            $data = $this->owner->analysisOfMiratext($this->owner->getUrl());
            $statRecord->setAttributes($data);
            return $statRecord->save();
        } catch (\Exception $e) {
            \Yii::error($e);
            return false;
        }
    }

    public function updateStatisticData()
    {
        try {
//            $this->owner->attachBehaviors([
//                ParserBehavior::class,
//                WordsSearcherBehavior::class
//            ]);

            $data = $this->owner->analyzeArticleHeadersOnNausea();

            $analysisData = $this->owner->analysisArticleContent($this->owner->getUrl());

            if(!$analysisData || !$data) {
                return false;
            }

            $statRecord = $this->owner->nauseaOfArticle ? $this->owner->nauseaOfArticle : new NauseaOfArticle();

            $statRecord->article_id = $this->owner->id;
            $statRecord->setAttributes($data);

            $statRecord->baden_points = $analysisData['badenPoints'];
            $statRecord->bigram = ArrayHelper::getValue($analysisData['bigram'], 0, 0);
            $statRecord->trigram = ArrayHelper::getValue($analysisData['trigram'], 0, 0);
            $statRecord->word_density = ArrayHelper::getValue($analysisData['density'], 0, 0);
            $statRecord->save();

            return $statRecord;
        } catch (Exception $e) {
            \Yii::error($e);
            return false;
        }
    }

    public function analysisOfMiratext(string $url)
    {
        $this->content = $this->owner->content;

        $response = $this->sendRequest('seo_analiz_text', [
            'data[Article][content_url]' => 'https://' . \Yii::$app->params['currentSiteHost'] . $url,
            'data[Article][checkType]' => 'url',
            'data[Article][is_export]' => 0,
            'sumit-button' => 'Отправить+запрос'
        ]);

        $result = $this->parse($response);

        return $result;
    }

    /**
    * @param string $action
    * @param array $data
    * @return array|mixed
    * @throws \Exception
    */
    protected function sendRequest($action, array $data = [])
    {
        $data['action'] = $action;

        $url = self::ENDPOINT . '/' . $action;

        $httpClient = new Client();
        $request = $httpClient->post($url, $data)->send();

        if($request->statusCode == 200) {
            return $request->content;
        }

        return 'Нет ответа от miratext';
    }

    protected function parse(string $content)
    {
        $data = [];

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);

        $doc->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $xpath = new \DomXpath($doc);

        $cells = $xpath->query('//table/tr/td');
        
        if(!isset($cells[9])) {
            return false;
        }

        $data['miratext_water'] = str_replace('%', '', $cells[9]->nodeValue);
        
        $tables = $xpath->query('//table');

        if(!isset($tables[2]) || !isset($tables[3]) || !isset($tables[5])) {
            return false;
        }

        $res = explode('<td class="text-center">', $tables[2]->C14N());
        $data['miratext_bigram'] = preg_replace('/[^\d;.]/ui', '', strip_tags($res[2]) );

        $res = explode('<td class="text-center">', $tables[3]->C14N());
        $data['miratext_trigram'] = preg_replace('/[^\d;.]/ui', '', strip_tags($res[2]) );

        $data['miratext_words'] = '';
        $res = explode('<tr>', $tables[5]->C14N());
        unset($res[0], $res[1]);
        $data['miratext_words'] = '';

        foreach ($res as $key => $value) {
            $value = str_replace('</td>', ';', $value);
            $value = preg_replace('/[^ a-zа-яё\d;.%]/ui', '', strip_tags($value) );
            $data['miratext_words'] = $data['miratext_words'] . $value;
        }

        return $data;
    }
}
