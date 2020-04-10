<?php


namespace console\controllers;


use common\models\Article;
use console\helpers\Console;
use console\helpers\YandexRelatedHelper;
use yii\console\Controller;
use yii\console\ExitCode;


/**
 * GlobalDeleteEmptyTagFromContentController
 * Its for delete empty tags from article content
 * for run php  yii global-delete-empty-tag-from-content                    delete from all articles
 * for run php  yii global-delete-empty-tag-from-content  id                delete by id
 * for run php  yii global-delete-empty-tag-from-content  null  tagName     delete by all articles by given tag name (default tagName => p)
 * for run php  yii global-delete-empty-tag-from-content  id  tagName       delete by by id and given tag name (default tagName => p)
 */
class GlobalDeleteEmptyTagFromContentController extends ConsoleController
{
    /**
     * @param null $id
     * @param string $tag
     * @return int
     */
    public function actionIndex($id = null, $tag = 'p')
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand();

        $openTag =  "<{$tag}>";
        $closeTag =  "</{$tag}>";

        $articles = Article::find()->where(['OR',
            ['LIKE', 'content', $openTag.$closeTag],
            ['LIKE', 'content', $openTag . ' ' . $closeTag],
            ['LIKE', 'content', $openTag . '&nbsp;' . $closeTag]
        ]);



        if($id) $articles->andWhere(['id' => $id]);

        $countArticles = $articles->count();

        $this->console->output('Всего ' . $countArticles . ' наидено статьеи с пустим тегам');
        $this->console->startProgress(1, $countArticles);

        $counterProgress = 1;

        foreach ($articles->each(100) as $article) {

            preg_match_all( $this->getRegExpPattern($openTag, $closeTag), $article->content, $data);


            if(!empty($data[0])) {
                foreach ($data[0] as $match) {
                    $article->content = str_replace($match, '', $article->content);
                }

               \Yii::$app->db->createCommand("UPDATE `articles` SET `content`='". $article->content ."' WHERE id={$article->id}")->execute();
                //$article->save(false);
                $this->console->output('Article ' . $article->id);
            }

            Console::updateProgress($counterProgress++, $countArticles);
        }

        $this->console->endProgress();

        $this->stopCommand();
        return ExitCode::OK;

    }

    public function getRegExpPattern($openTag, $closeTag) {
        $closeTag = str_replace('/', '\/', $closeTag);
        //|(" . $openTag. ' ' .$closeTag . ")
        return "/(" . $openTag.$closeTag . ")|(" . $openTag. '&nbsp;' .$closeTag . ")|(" . $openTag. ' ' .$closeTag . ")/si";
    }

}