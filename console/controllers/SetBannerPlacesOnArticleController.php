<?php
namespace console\controllers;

use Yii;
use common\models\{ Article };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use common\helpers\FilesHelper;
use yii\helpers\Html;

class SetBannerPlacesOnArticleController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '2024M');
        ini_set('max_execution_time', 0);

        $this->startCommand();
        $this->console->output('Расстановка баннерных мест');

        self::processArticles();
        
        $this->console->output('Баннеры установлены');

        $this->stopCommand();
        return ExitCode::OK;
    }

    private function processArticles()
    {
        $articles = Article::find();

        $countArticles = $articles->count();

        $this->console->output('Всего ' . $countArticles . ' статей');

        $counterProgress = 1;
        
        
        $this->console->startProgress($counterProgress, $countArticles);
        

        foreach ($this->getArticles() as $article) {

            $article->deleteBannerShortcodes();

            $article->insertBannerShortcodes();

            $article->save(false);
        
            $this->console->updateProgress($counterProgress++, $countArticles);
            $article = null;
            unset($article);
        }

        $this->console->endProgress();


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
