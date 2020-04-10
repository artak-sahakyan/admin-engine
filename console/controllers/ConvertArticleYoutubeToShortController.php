<?php

namespace console\controllers;

use common\models\Article;
use common\models\ArticleYoutube;
use console\helpers\QueryHelper;
use Yii;

class ConvertArticleYoutubeToShortController extends ConsoleController
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
        $this->startCommand();

        if ($this->readOnly) {
            $this->console->output('Script started readonly mode');
        }

        $this->processArticles();

        $this->stopCommand();
    }

    public function actionFetchOembed()
    {
        $this->startCommand();

        if ($this->readOnly) {
            $this->console->output('Script started readonly mode');
        }

        $this->processFetchOembed();

        $this->stopCommand();
    }

    // Articles replace html to short.
    // Articles shorttag without DB added.
    private function processArticles()
    {
        $matched = false;

        $query = Article::find()->where(['like', 'content', 'youtube'])->orderBy('id');
        foreach (QueryHelper::getRows($query) as $article) {

            // find html and replace to short
            // check patterns
            $patterns = [
                '~<iframe.*?src="(.+?youtube.com/embed/(.{11,11})\??.*?)".+?</iframe>~',
                '~<object.*?src="(.+?youtube.com/v/(.{11,11})\??.*?)".+?</object>~s',
            ];
            foreach ($patterns as $pattern) {
                preg_match_all($pattern, $article->content, $htmlMatches);

                if(empty($htmlMatches[0])) {
                    continue;
                } else {
                    break;
                }
            }

            // handle with pattern
            if (!empty($htmlMatches[0])) {
                $matched = true;

                foreach ($htmlMatches[0] as $key => $match) {
                    $url = $htmlMatches[1][$key];
                    $link = $htmlMatches[2][$key];

                    $youtube = ArticleYoutube::find()->where(['link' => $link])->limit(1)->one();
                    if (!$youtube) {
                        $youtube = new ArticleYoutube();

                        $youtube->article_id = $article->id;
                        $youtube->link = $link;
                        $youtube->missed_position = null;
                        $youtube->missed_updated_at = null;

                        if (!$this->readOnly) {
                            $youtube->save();
                        }
                    }

                    $replace = '[lazyyoutube id="' . $link . '"]';
                    $article->content = str_replace($match, $replace, $article->content);
                    $this->console->output('Article ' . $article->id . ' ' . $match . ' replaced to ' . $replace);
                }
            }

            if (!$matched) {
                $this->console->output('Error: article ' . $article->id . ' preg_match non matched');
            }

            // save without handlers
            if ($matched && !$this->readOnly) {
                Yii::$app->db->createCommand("
                    UPDATE `articles`
                    SET `content` = " . Yii::$app->db->quoteValue($article->content) . "
                    WHERE `id` = " . (int)$article->id . "
                ")->execute();
            }
        }
    }

    private function processFetchOembed()
    {
        $query = ArticleYoutube::find()->where(['title' => '', 'cover' => '',])
            ->limit(null)
            ->andWhere('missed_updated_at is null');

        foreach($query->each() as $youtube) {
            $resp = ArticleYoutube::fetchOembedData($youtube->link);

            if (!empty($resp['data']['title'])) {
                $youtube->title = $resp['data']['title'];
            }
            if (!empty($resp['data']['thumbnail_url'])) {
                $youtube->cover = $resp['data']['thumbnail_url'];
            }

            if (sizeof($resp['data']) == 0) {
                // fetchOembed - not found
                $youtube->missed_position = 0;
                $youtube->missed_updated_at = time();
            }

            if (!$this->readOnly) {
                $youtube->save();
            }

            // request pause for youtube request
            usleep(rand(3, 6) . 00000);

            $this->console->output('Artcile check ' . $youtube->article_id);
            $this->console->output('Oembed requested url ' . ArticleYoutube::OEMBED_URL . ArticleYoutube::EMBED_URL . $youtube->link);
            if (sizeof($resp['data']) == 0) {
                $this->console->output('Error: cat not response from fetchOembed for article ' . $youtube->article_id);
            }
        }


    }
}