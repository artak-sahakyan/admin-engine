<?php
namespace console\controllers;

use Yii;
use common\models\{ Article, NauseaOfArticle };
use yii\console\ExitCode;
use \console\helpers\Console;

class GetNauseaController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand(); 
        $this->console->output('Обработка заголовков');

        $ids = NauseaOfArticle::find()->select(['article_id'])->column();

        $articles = Article::find()
                ->where(['not in', 'id', $ids])
                ->orderBy('id')
                ->limit(500)
                ->all();

        foreach ($articles as $article) {
            $message = ($article->updateStatisticData()) ? 'Обновлен ' : 'Ошибка ';
            $this->console->output($message . $article->id);
        }
        
        $this->stopCommand();
        return ExitCode::OK;
    }
}
