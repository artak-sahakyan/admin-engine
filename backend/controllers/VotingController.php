<?php
namespace backend\controllers;

use backend\events\ControllerBeforeRenderEvent;
use common\helpers\ArrayHelper;
use Yii;
use common\models\{ 
    Voting, 
    VotingLink,
    BannerGroup, 
    ArticleCategory, 
    VotingAnswer, 
    Article
};
use backend\controllers\AdminController;
use backend\events\ControllerModelSaveEvent;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * VotingController implements the CRUD actions for Voting model.
 */
class VotingController extends AdminController
{
    public function init()
    {
        $this->modelClass = Voting::class;
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'votingSave']);
        $this->on(static::BEFORE_RENDER_EVENT, [$this, 'addData']);
    }

    public function addData(ControllerBeforeRenderEvent $event)
    {
        $model = $event->args['model'];

        $model->votingArticles = Article::find()
            ->select(['id', 'title'])
            ->indexBy('title')
            ->where(['id' => ArrayHelper::map($model->votingVsArticle, 'link_id', 'link_id')])
            ->column();

    }

    public function votingSave(ControllerModelSaveEvent $event)
    {
        $inputData = ($post = $event->post) ? $post : Yii::$app->request->post('Voting');

        VotingAnswer::deleteAll(['voting_id' => $event->model->id]);
        if(!empty($inputData['answers'])) {
            foreach ($inputData['answers'] as $key => $answer) {
                $votingAnswer = new VotingAnswer();
                $votingAnswer->title = $answer;
                $votingAnswer->voting_id = $event->model->id;
                $votingAnswer->save();
            }
        }

        $bannerGroup_ids = $inputData['bannerGroups'];
        $bannerGroup_ids = (is_array($bannerGroup_ids)) ? $bannerGroup_ids : [$bannerGroup_ids];
        $event->model->updateLinks($bannerGroup_ids, 'bannerGroups', BannerGroup::class, VotingLink::BANNERGROUP);

        $articleCategory_ids = $inputData['articleCategories'];
        $articleCategory_ids = (is_array($articleCategory_ids)) ? $articleCategory_ids : [$articleCategory_ids];
        $event->model->updateLinks($articleCategory_ids, 'articleCategories', ArticleCategory::class, VotingLink::CATEGORY);

        $article_ids = $inputData['votingArticles'];

        $article_ids = (is_array($article_ids)) ? $article_ids : [$article_ids];

        $event->model->updateLinks($article_ids, 'articles', Article::class, VotingLink::ARTICLE);
    }

    public function actionCreateVoting() {

        $request = Yii::$app->request;
        $voting = $request->post('form');
        $select2 = json_decode($request->post('select2'));

        $values = [];
        parse_str($voting, $values);

        if($select2) {
            foreach ($select2 as $k => $val) {
                $values['Voting'][$k] = $val;
            }
        }

        $model = new Voting(['scenario' => Voting::SCENARIO_DEFAULT]);

        if (Yii::$app->request->isAjax && $model->load($values)) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            if($errors = ActiveForm::validate($model)) {
                return $errors;
            } else {
                $this->trigger(static::MODEL_AFTER_SAVE_EVENT, new ControllerModelSaveEvent(['model' => $model, 'insert' => false, 'saved' => $model->save(), 'post' => $values['Voting']]));
                return 'success';
            }
        }
    }
}
