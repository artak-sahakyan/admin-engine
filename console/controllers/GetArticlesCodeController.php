<?php
namespace console\controllers;

use Yii;
use common\models\Article;
use yii\console\ExitCode;
use \console\helpers\Console;

class GetArticlesCodeController extends ConsoleController
{
    private $baseUrl = null;
    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->baseUrl = Yii::$app->urlManager->baseUrl;

        $this->startCommand(); 
        $this->console->output('Проверка доступности страниц');
        $this->console->output('Ожидайте, статьи проверяются. Найденные ошибки будут отображены ниже');
                
        foreach ($this->getArticles() as $article) {
            $url = self::getUrl($article);
            $res = $this->checkArticleCode($url);

            if(!$res) {
                $this->console->output("Ошибка: " . $url);
            }
        }
        
        $this->stopCommand();
        return ExitCode::OK;
    }

    private function getUrl($article)
    {
        return $this->baseUrl . '/' . $article['id'] . '-' . $article['slug'] . '.html';
    }

    private function checkArticleCode($url)
    {
        $res = true;
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 200) {
            $res = false;
        }

        curl_close($handle);
        return $res;
    }

    private function getArticles($limit = null)
    {
        $perPage = 100;
        $page = 0;
        $i = 0;

        while ($articles = Article::find()->orderBy('id')->where(['is_published' => 1])->select('id, slug')->limit($perPage)->offset($perPage * $page)->asArray()->all()) {
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
}
