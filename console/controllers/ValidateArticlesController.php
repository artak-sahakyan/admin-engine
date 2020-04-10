<?php
namespace console\controllers;

use Yii;
use common\models\Article;
use yii\console\ExitCode;
use \console\helpers\Console;

class ValidateArticlesController extends ConsoleController
{
    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand(); 
        $this->console->output('Валидация разметки статей');
                
        foreach ($this->getArticles() as $article) {
            $message = ($article->validateHtml() ? 'Проверен ' : 'Ошибка ');
            $this->console->output($message . $article->id);
        }
        
        $this->stopCommand();
        return ExitCode::OK;
    }

    private function getArticles($limit = null)
    {
        $perPage = 100;
        $page = 0;
        $i = 0;

        while ($articles = Article::find()->orderBy('id')->limit($perPage)->offset($perPage * $page)->all()) {
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
