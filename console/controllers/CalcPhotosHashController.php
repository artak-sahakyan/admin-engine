<?php
namespace console\controllers;

use common\models\{ Article, ArticlePhotoHash };
use yii\console\{ Controller, ExitCode };
use yii\helpers\Console;
use Yii;

class CalcPhotosHashController extends Controller
{
    private $console;
    private $type;

    public function actionIndex(int $type)
    {
        $this->type = $type;
        $this->console = new \console\helpers\Console();
        $this->console->clearScreen();
        $this->console->createLog(self::class);
        $this->console->output('Вычисление хэша изображений');

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $photos = self::processArticles();

        if($photos > 0) {
            $this->console->output('Найдено ' . $photos . ' новых изображений');
        } else {
            $this->console->output('Не найдено новых изображений');
        }
        $this->console->exitLog();
        return ExitCode::OK;
    }

    /**
     * @return array
     */
    private function processArticles()
    {
        $ids = ArticlePhotoHash::find()
            ->select(['article_id'])
            ->where(['type' => $this->type])
            ->column();

        $time = time() - 60 * 60 * 24 * 2;
        $articles = Article::find()
            ->andWhere(['not in', 'id', $ids])
            ->orWhere(['>=', 'updated_at', $time])
            ->orderBy('id');

        if($this->type === 1) {
            $countArticles = Yii::$app->db->createCommand('select  sum((char_length(content) - char_length(replace(content,\'<img\',\'\'))) div char_length(\'<img\')) as count from articles')->queryColumn();
            $countArticles = $countArticles[0];
        } else {
            $countArticles = $articles->count();
        }

        $this->console->output('Всего ' . $countArticles . ' изображений в статьях');
        $this->console->startProgress(1, $countArticles);

        $counterProgress = 1;
        $photos = 0;

        foreach ($articles->each(100) as $article) {
            
            ArticlePhotoHash::calcHashAndSave($article, $this->type);

            Console::updateProgress($counterProgress++, $countArticles);
            $photos++;
        }

        $this->console->endProgress();

        return $photos;
    }

}