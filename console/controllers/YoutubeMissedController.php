<?php
namespace console\controllers;

use console\helpers\QueryHelper;
use Yii;
use common\models\{ Article, ArticleYoutube };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;

class YoutubeMissedController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex($missedId = 0, $missedUpdate = false)
    {
        ini_set('memory_limit', '64M');
        ini_set('max_execution_time', 0);

        $this->startCommand();

        $this->console->output('Поиск битых ссылок Youtube');

        $brokenLinks = self::processArticles($missedId, $missedUpdate);

        if($missedId > 0) {
            ArticleYoutube::updateAll(
                ['missed_position' => null], ['article_id' => $missedId]
            );
        } else {
            ArticleYoutube::updateAll(['missed_position' => null], 'missed_position is NOT NULL');
        }
        
        if(count($brokenLinks) > 0) {
            $this->console->output('Найдено ' . count($brokenLinks) . ' битых ссылок');

            // insert or update
            foreach ($brokenLinks as $item) {
                $articleYoutube = ArticleYoutube::find()->where(['article_id' => $item['article_id'], 'link' => $item['link']])->limit(1)->one();
                if ($articleYoutube !== null) {
                    $articleYoutube->missed_position = $item['missed_position'];
                    $articleYoutube->missed_updated_at = $item['missed_updated_at'];
                    $articleYoutube->save();
                } else {
                    $articleYoutube = new ArticleYoutube;
                    $articleYoutube->article_id = $item['article_id'];
                    $articleYoutube->link = $item['link'];
                    $articleYoutube->missed_position = $item['missed_position'];
                    $articleYoutube->missed_updated_at = $item['missed_updated_at'];
                    $articleYoutube->save();
                }
            }
        } else {
            $this->console->output('Не найдено битых ссылок');
        }

        $this->stopCommand();
        return ExitCode::OK;
    }

    private function processArticles($missedId, $missedUpdate)
    {
        $where = [];
        if ($missedId > 0) {
            $where = ['id' => $missedId];
        } elseif($missedUpdate > 0) {
            $ids = ArticleYoutube::find()
                ->select(['article_id'])
                ->orderBy('id')
                ->column();

            $where = ['in', 'id', $ids];
        } else {
            $where = ['like', 'content', 'youtube'];
        }

        $countVideo = Yii::$app->db->createCommand('select  sum((char_length(content) - char_length(replace(content,\'youtube\',\'\'))) div char_length(\'youtube\')) as count from articles')->queryColumn();
        $countVideo = $countVideo[0];
        $this->console->output('Всего ' . $countVideo . ' видео в статьях');

        $counterProgress = 1;
        $brokenLinks = [];
        $time = time();
        $this->console->startProgress($counterProgress, $countVideo);

        $query = Article::find()->where($where)->orderBy('id');
        foreach (QueryHelper::getRows($query) as $article) {

            $content = $article->content;
            $article_id = $article->id;

            $urls = ArticleYoutube::getUrls($content);

            foreach ($urls as $key => $url) {
                $result = ArticleYoutube::checkVideo($url);
                
                if(!$result) {
                    $brokenLinks[] = [
                        'link'              => $url,
                        'article_id'        => $article_id,
                        'missed_position'   => $key,
                        'missed_updated_at' => $time
                    ];

                    $this->console->output('Found broken link in article id ' . $article->id . ' link ' . $url);
                }

                Console::updateProgress($counterProgress++, $countVideo);
            }
        }
        $this->console->endProgress();

        return $brokenLinks;
    }
}
