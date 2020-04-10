<?php
namespace console\controllers;

use backend\models\CommonData;
use Yii;
use common\models\{ Article, ArticleSpelling };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use Yandex\Speller\SpellerClient;

class ArticleDoubleBannerPlaceController extends ConsoleController
{

    public $distanceBetweenBlocks = 1000;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'distanceBetweenBlocks',
        ]);
    }

    public function actionIndex()
    {
        $this->startCommand();

        $articleIds = self::processFindDoubleBannerPlace();

        CommonData::value('article-double-banner-place_ids', $articleIds);

        $this->stopCommand();
        return ExitCode::OK;
    }

    /**
     * @return array
     */
    private function processFindDoubleBannerPlace()
    {
        $countArticles = Article::find()->count();

        // reset flag
         Yii::$app->db->createCommand("
            UPDATE `". Article::tableName() ."`
            SET `is_double_banner_place_manual_fix` = 0
        ");

        $counterProgress = 1;

        $this->console->startProgress($counterProgress, $countArticles);

        $articleIds = [];
        foreach ($this->getArticles() as $article) {
            $content = $article['content'];

            $pattern = '~\[banner alias="article_related_(?:1|2|3)" action="place"\]~';
            preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
            $found = false;
            $prevPosition = null;
            foreach ($matches[0] as $match) {
                $position = $match[1];

                if (!isset($prevPosition)) {
                    $prevPosition = $position;
                } else {
                    if ($position - $prevPosition < $this->distanceBetweenBlocks) {
                        $found = true;
                    }
                    $prevPosition = $position;
                }
            }

            if ($found) {
                $articleIds[] = $article->id;
            }

            Console::updateProgress($counterProgress++, $countArticles);
        }

        $this->console->endProgress();

        return $articleIds;
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
