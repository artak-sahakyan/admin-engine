<?php


namespace console\controllers;

use Yii;
use common\models\{ Article, ArticleRelatedYandex };
use console\helpers\{ Console, YandexRelatedHelper };
use yii\console\{ Controller, ExitCode };

/**
 * GlobalRelatedYandexController
 * Its for getting from yandexXML related articles and set in our db
 * for run php  yii global-related-yandex  0                   update all
 * for run php  yii global-related-yandex  0 null user key     update all by given user and key
 * for run php  yii global-related-yandex  1                   test
 * for run php  yii global-related-yandex  2 100               update by id
 */
class GlobalRelatedYandexController extends ConsoleController
{
    /**
     * @param int $testMode
     * @param null $id
     * @param null $user
     * @param null $key
     */
    public function actionIndex($testMode = 0, $id = null, $user = null, $key = null)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 1900);

        $this->startCommand();

        if($user && $key) {
            YandexRelatedHelper::$authParams['user'] = $user;
            YandexRelatedHelper::$authParams['key'] = $key;
        }

        if(!$testMode) {
            $query = Article::find()->where(['not in', 'id', ArticleRelatedYandex::find()
                ->select('article_id')
                ->distinct()->groupBy('article_id')]);
            YandexRelatedHelper::updateBigData($query);
            $this->console->output("FINISHED");
        } else if($testMode == 1) {
            YandexRelatedHelper::updateArticle();
        } else if($testMode == 2 && (int)$id) {
            YandexRelatedHelper::updateArticle((int)$id);
        } else {
            $this->console->output("Wrong params");
        }

        $this->stopCommand();
        return ExitCode::OK;

    }
}