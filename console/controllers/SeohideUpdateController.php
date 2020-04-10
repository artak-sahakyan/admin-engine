<?php
namespace console\controllers;

use common\models\{ Article, ArticlePhotoHash };
use yii\console\{ Controller, ExitCode };
use yii\helpers\Console;
use Yii;

class SeohideUpdateController extends ConsoleController
{

    public function actionIndex()
    {
        $this->startCommand();
        $this->console->output('Обновление сеохайд ссылок');

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $links = self::processArticles();

        if($links > 0) {
            $this->console->output('Найдено ' . $links . ' ссылок');
        } else {
            $this->console->output('Не найдено ссылок');
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
        ->where(['like', 'content', '%data-key=%', false])
        ->orderBy('id');


        $countArticles = $articles->count();

        $this->console->output('Всего ' . $countArticles . ' ссылок');
        $this->console->startProgress(1, $countArticles);

        $counterProgress = 1;
        $links = 0;

        foreach ($articles->each(100) as $article) {
            
            preg_match_all( '/<a[^>]+?[^>]+>(.*?)<\/a>/i',$article->content, $urls);

            foreach ($urls[0] as $url) {
                preg_match('#data-key="([^"]+)"#i', $url, $key);

                if(isset($key[1])) {
                    $arr = explode('>', $url);
                    $text = str_replace('</a', '', $arr[1]);
                
                    $seohide = '[seohide title="'.$text.'" url="'.base64_decode($key[1]).'"]';

                    $article->content = str_replace($url, $seohide, $article->content);
                    $article->save();
                }
            }

            Console::updateProgress($counterProgress++, $countArticles);
            $links++;
        }

        $this->console->endProgress();

        return $links;
    }
}
