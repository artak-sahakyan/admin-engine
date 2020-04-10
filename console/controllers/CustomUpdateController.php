<?php
namespace console\controllers;

use Yii;
use common\models\Article;
use yii\console\ExitCode;
use \console\helpers\Console;

class CustomUpdateController extends ConsoleController
{
    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand(); 
        $this->console->output('Кастомная команда обработки статей');
                
        foreach ($this->getArticles() as $article) {

            $this->console->output('Обработка ' . $article->id);

            $content = $article->content;

            $content = preg_replace('#<source(.*?)>#', '', $content);
            $content = preg_replace('#<picture(.*?)>#', '', $content);
            $content = preg_replace('#<div class="image__loading"(.*?)></div>#', '', $content);
            $content = preg_replace('#<img alt=(.*?).jpg">#', '', $content);
            $content = str_replace(['</picture>', '<noscript>', '</noscript>', '</source>'], '', $content);

            Yii::$app->db->createCommand()->update(Article::tableName(), ['content' => $content], 'id = ' . $article->id)->execute();

            $article = null;
            unset($article);

        }
        
        $this->stopCommand();
        return ExitCode::OK;
    }

    private function getArticles($limit = null)
    {
        $perPage = 100;
        $page = 0;
        $i = 0;

        while ($articles = Article::find()->where(['like', 'content', '<picture'])->orderBy('id')->limit($perPage)->offset($perPage * $page)->all()) {
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
