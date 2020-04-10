<?php

namespace console\controllers;

use console\helpers\Console;
use Yii;
use common\models\Article;
use yii\console\Controller;
use yii\console\ExitCode;

class ArticleImageSetDominantController extends ConsoleController
{
    public $readOnly = true;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'readOnly',
        ]);
    }

    public function actionIndex()
    {
        ini_set('max_execution_time', 0);

        $this->startCommand();

        if ($this->readOnly) {
            $this->console->output('Script running is readonly mode');
        }
        $this->console->output('Skip all not empty articles');

        $articles = Article::find()->where("JSON_TYPE(JSON_EXTRACT(image_color, '$')) = 'NULL' OR `image_color` = 'null'");
        $countArticles = $articles->count();

        $counterProgress = 1;
        if ($countArticles > 0) {
            $this->console->startProgress($counterProgress, $countArticles);
        }

        foreach ($articles->each(100) as $article) {

            $dominantColorRgb = $article->imageDominantColor();
            if ($dominantColorRgb === false) {
                continue;
            }

            $backgroundColorRgb = $article->imageBackgroundColor();
            if ($backgroundColorRgb === false) {
                continue;
            }

            $imageColor = [];
            $imageColor['d'] = $dominantColorRgb;
            $imageColor['b'] = $backgroundColorRgb;
            $imageColor = json_encode($imageColor);
            $this->console->output("\nArticle " . $article->id . "\n" . $imageColor . "\n");
            if (!$this->readOnly) {
                // save to raw sql

                Yii::$app->db->createCommand("
                    UPDATE `articles`
                    SET `image_color` = " . Yii::$app->db->quoteValue($imageColor) . "
                    WHERE `id` = " . (int)$article->id . "
                ")->execute();
            }

            $this->console->updateProgress($counterProgress++, $countArticles);
        }
        $this->console->endProgress();

        $this->stopCommand();
        return ExitCode::OK;
    }
}