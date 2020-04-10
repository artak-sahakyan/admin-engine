<?php
namespace console\controllers;

use Yii;
use common\models\{ Article, NauseaOfArticle };
use yii\console\ExitCode;
use \console\helpers\Console;

class GetMiratextController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand(); 
        $this->console->output('Получение данных с miratext');

        $ids = NauseaOfArticle::find()->select(['article_id'])->where(['not', ['miratext_water' => null]])->column();

        $articles = Article::find()
                ->where(['not in', 'id', $ids])
                ->orderBy('id')
                ->limit(500)
                ->orderBy('id DESC')
                ->all();
                
        foreach ($articles as $article) {
            $message = ($article->updateMiratextData() ? 'Обновлен ' : 'Ошибка ');
            $this->console->output($message . $article->id);
        }
        
        $this->stopCommand();
        return ExitCode::OK;
    }
}
