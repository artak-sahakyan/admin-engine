<?php

namespace common\helpers;

use common\models\Article;
use common\models\ArticleYoutube;

class YoutubeHelper
{
    /**
     * Parse content and fetch youtube title,cover save our db. So check youtube valid.
     *
     * @param Article $article
     */
    public static function loadYoutube(Article $article)
    {
        preg_match_all('~\[lazyyoutube id=(?:\"|\&quot;)(.+?)(?:\"|\&quot;)]~', $article->content, $shortMatches);
        if (!empty($shortMatches[1])) {
            foreach ($shortMatches[1] as $key => $link) {

                $resp = ArticleYoutube::fetchOembedData($link);

                $youtube = ArticleYoutube::find()->where(['link' => $link])->limit(1)->one();
                if (!$youtube) {
                    $youtube = new ArticleYoutube();

                    $youtube->article_id = $article->id;
                    $youtube->link = $link;

                    $youtube->missed_position = null;
                    $youtube->missed_updated_at = null;
                }

                if (!empty($resp['data']['title'])) {
                    $youtube->title = $resp['data']['title'];
                }
                if (!empty($resp['data']['thumbnail_url'])) {
                    $youtube->cover = $resp['data']['thumbnail_url'];
                }

                if (sizeof($resp['data']) == 0) {
                    // fetchOembed - not found, video not valid
                    $youtube->missed_position = 0;
                    $youtube->missed_updated_at = time();
                }

                $youtube->save();
            }
        }
    }
}