<?php


namespace console\controllers;


use common\models\oldSovets\OldProjectArticle;
use Yii;
use yii\console\Controller;



/**
 * GetRelatedYandexFromOldController
 * Its import from old project related yandex articles
 * for run php yii related-yandex-from-old
 */
class RelatedYandexFromOldController extends Controller
{
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        //$query = ArticleRelatedYandex::find();

        $query =  OldProjectArticle::find()->with('relatedYandex');

        $clone = clone $query;
        $count = $clone->count();

        foreach($query->batch(100) as $oldArticles) {

            foreach ($oldArticles as $article) {
                    $count--;
                    echo 'Count : ' . $count .  "\n";
                    if($relatedArticles = $article->relatedYandex) {

                      \common\models\ArticleRelatedYandex::deleteAll(['article_id' => $article->id]);

                        foreach ($relatedArticles as $related) {
                            $newRelated = new \common\models\ArticleRelatedYandex();
                            $newRelated->attributes = $related->attributes;
                            $newRelated->save();
                            echo "article {$article->id}  related {$related->related_article_id} \n";

                            if ($errors = $newRelated->getErrorSummary(true)) {
                                echo "<pre>";
                                print_r($errors);
                                echo "</pre>";
                            }
                        }
                    }
                }
            }
        }
}
