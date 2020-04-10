<?php
namespace console\controllers;

use common\models\{ Article, ArticlePhotoHash };
use yii\console\{ Controller, ExitCode };
use yii\helpers\Console;
use Yii;

class GlobalRewriteReviewsController extends ConsoleController
{
    public function actionIndex()
    {
        $this->startCommand();
        $this->console->output('Обновление отзывов');

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $reviews = self::processArticles();

        if($reviews > 0) {
            $this->console->output('Найдено ' . $reviews . ' отзывов');
        } else {
            $this->console->output('Не найдено отзывов');
        }

        $this->stopCommand();
        return ExitCode::OK;
    }

    /**
     * @return array
     */
    private function processArticles()
    {
        $articles = Article::find()
        ->where(['like', 'content', '%<blockquote%', false])
        ->orderBy('id');


        $countArticles = $articles->count();

        $this->console->output('Всего ' . $countArticles . ' отзывов');
        $this->console->startProgress(1, $countArticles);

        $counterProgress = 1;
        $reviews = 0;

        foreach ($articles->each(100) as $article) {
            
            preg_match_all( "'<blockquote>(.*?)</blockquote>'si",$article->content, $data);

            foreach ($data[0] as $review) {
              preg_match_all("'<p>(.*?)</p>'si",$review, $items);
              if(empty($items)) {
                 preg_match_all("'<span(.*?)</span>'si",$review, $items);
              }

              if(isset($items[1]) && isset($items[1][0]) && isset($items[1][1])) {
            
                $shortcode = '[review name="'.$items[1][0].'" content="'.$items[1][1].'"]';

                $article->content = str_replace($review, $shortcode, $article->content);
                $article->save();
            }
        }

        Console::updateProgress($counterProgress++, $countArticles);
        $reviews++;
    }

    $this->console->endProgress();

    return $reviews;
}
}