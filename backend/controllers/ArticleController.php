<?php
namespace backend\controllers;

use backend\events\{
    ControllerBeforeRenderEvent,
    ControllerModelSaveEvent,
    ControllerUnpublishedEvent
};

use common\helpers\AireeHelper;
use common\helpers\UrlHelper;
use common\models\{
    ArticleCategory,
    ArticleMeta,
    ShowGridColumn,
    NauseaOfArticle,
    Voting
};

use common\behaviors\{
    MarkuperBehavior,
    ParserBehavior,
    UpdateLinksBehavior,
    WordsSearcherBehavior
};

use yii\db\Query;
use yii\helpers\{
    ArrayHelper,
    Html,
    Url
};

use backend\models\{
    Article,
    ArticleSearch,
    CommonData,
    NauseaOfArticleSearch
};

use Yii;
use common\helpers\FilesHelper;
use yii\httpclient\Client;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\base\Exception;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends AdminController
{

    public function beforeAction($action)
    {
        if ($action->id == 'upload-images') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'browse-images' => [
                'class' => 'backend\widgets\ckeditor\actions\BrowseAction',
                'quality' => 70,
                'maxWidth' => 700,
                'maxHeight' => 700,
                'useHash' => true,
                'url' => '/photos/article-contents/compress/',
                'path' => '@siteFrontend/web/photos/article-contents/compress/',
            ],
            'upload-images' => [
                'class' => 'backend\widgets\ckeditor\actions\UploadActionCustom',
                'quality' => 70,
                'maxWidth' => 700,
                'maxHeight' => 700,
                'useHash' => true,
                'url' => '/photos/article-contents/compress/',
                'path' => '@siteFrontend/web/photos/article-contents/origin/',
                'pathCompress' => '@siteFrontend/web/photos/article-contents/compress/',
            ],
        ]);
    }


    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['children-list', 'delete-images', 'get-article-urls-for-copy', 'search-by-title']
            ]
        ]);
    }

    public function modelSave(ControllerModelSaveEvent $event)
    {
        $event->model->setTimeFormat(['published_at', 'anounce_end_date', 'yandex_origin_date', 'imported_at', 'ready_publish_date']);
        $event->model->setCategoryId();

        if(!$event->model->admin_id) {
            $event->model->admin_id = Yii::$app->user->id;
        }

        $articleInput = Yii::$app->request->post('Article');

        try {
            if(isset($articleInput['id']) && $articleInput['id'] != $event->model->id) {
                $event->model->changeId($articleInput['id']);
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', $e->getMessage());
        } 

        if(isset($articleInput['is_published']) && $articleInput['is_published'] && !$event->model->publisher_id){
            $event->model->publisher_id = Yii::$app->user->id;
        }

        if(Yii::$app->request->post('resetBanners') || !isset($event->model->id))
        {
            $event->model->deleteBannerShortcodes();
            $event->model->insertBannerShortcodes();
        }
    }

    public function addData(ControllerBeforeRenderEvent $event)
    {
        $event->args['model']->attachBehaviors([
            WordsSearcherBehavior::class,
        ]);

        $model = $event->args['model'];

        /* @var Article $model*/
        $event->args['articleMeta'] = $model->checkOrGetMeta();
        $event->args['articleBannerGroup'] = $model->checkOrGetBannerGroup();

        $modelVoting =  new \common\models\Voting(['scenario' => Voting::SCENARIO_ARTICLE]);
        $modelVoting->votingArticles = (!$model->isNewRecord) ? [$model->title => $model->id] : [];
        $event->args['modelVoting'] = $modelVoting;

        $model->loadCategoryLevels($model->category_id);
    }

    public function init()
    {
        $this->modelClass = Article::class;
        $this->on(static::MODEL_SAVE_EVENT, [$this, 'modelSave']);
        $this->on(static::BEFORE_RENDER_EVENT, [$this, 'addData']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'imageUpload']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'imageColor']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'contentStructure']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'updateRelatedModels']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'aireeClearCache']);

    }

    public function actionChildrenList($id)
    {

        $categories = ArticleCategory::findAll(['parent_id' => $id]);
        $result = '<option value="">Выберите подкатегорию</option>';
        if($categories) {
            foreach ($categories as $child) {
                $result .= Html::tag('option', $child->title, ['value' => $child->id]);
            }
        }
        return $result;

    }

    public function actionUpdateArticleStatistic($id)
    {
        $article = $this->findModel($id);

        $article->attachBehaviors([
            WordsSearcherBehavior::class,
            MarkuperBehavior::class,
            ParserBehavior::class
        ]);

        $article->updateStatisticData();
        return 1;
    }

    public function updateRelatedModels(ControllerModelSaveEvent $event)
    {

        $model = $event->model;
        /* @var Article $model*/
        $articleMeta = $model->checkOrGetMeta();
        $articleBannerGroup = $model->checkOrGetBannerGroup();


        if($event->saved) {

            if($articleMeta->load(Yii::$app->request->post())) {
                /* @var $articleMeta ArticleMeta */
                $articleMeta->save();
            }
        }
    }

    public function aireeClearCache(ControllerModelSaveEvent $event)
    {
        $model = $event->model;
        /* @var Article $model*/

        \common\helpers\AireeHelper::clearCache($model->id);
    }

    public function imageUpload(ControllerModelSaveEvent $event)
    {
        $model = $event->model;

        /* @var Article $model*/
        if($event->saved) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if( $model->imageFile) {
                $model->uploadImage();
            }
        }
    }

    public function contentStructure(ControllerModelSaveEvent $event) {

        /* @var Article $model */
        $model = $event->model;

        if ($event->saved) {
            $model->attachBehaviors([MarkuperBehavior::class]);
            $model->generateNavigationStructure();
        }
    }

    public function imageColor(ControllerModelSaveEvent $event)
    {
        /* @var Article $model */
        $model = $event->model;

        if ($event->saved) {

            $imageColor = [];
            $dominantColorRgb = $model->imageDominantColor();
            if ($dominantColorRgb) {
                // [d]ominant
                $imageColor['d'] = $dominantColorRgb;
            }

            $backgroundColorRgb = $model->imageBackgroundColor();
            if ($backgroundColorRgb) {
                // [b]ackground
                $imageColor['b'] = $backgroundColorRgb;
            }

            $model->image_color = $imageColor;
            $model->save();
        }
    }


    public function actionDeleteImages($id)
    {
        $result = ['status' => false];

        if($article = $this->findModel($id)) {
           $result =  [
               'status' => true,
               'value' => $article->deleteImages(true)
           ];
        }
        return json_encode($result);
    }

    public function actionUnpublished()
    {
        $params = ['unpublished_articles' => 1];
        $this->params = $params;
        $this->page = 'unpublished_articles';
        return $this->actionIndex();
    }

    public function actionHeaders()
    {
        $searchModel = new NauseaOfArticleSearch();
        $input = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($input);
        $limits = NauseaOfArticle::defaultValuesForSearch();

        $dataProvider->query = $dataProvider->query->addSelect(
            ['*,
            ((length(miratext_words )-length(replace(miratext_words ,";","")))/3) as countMiratextWords'
            ]);

        $dataProvider->sort->attributes['countMiratextWords'] = [
            'asc' => ['countMiratextWords' => SORT_ASC],
            'desc' => ['countMiratextWords' => SORT_DESC],
        ];


        foreach ($limits as $key => $value) {
            if(!empty($input['NauseaOfArticleSearch'][$key])) {
                $limits[$key] = $input['NauseaOfArticleSearch'][$key];
            }
        }

        return $this->render('headers', compact('searchModel', 'dataProvider', 'limits'));
    }

    public function actionRelatedYandexArticles()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('related-yandex-articles', compact('searchModel', 'dataProvider'));

    }

    public function actionDeleteRelatedArticles($id)
    {
        if ($model = $this->findModel($id)) {
            /* @var $model \common\models\Article */
            $model->unlinkAll('relatedYandexArticles', true);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdateRelated($id)
    {
        $sitePath = Yii::getAlias('@sitePath');
        exec("php $sitePath/yii global-related-yandex 2 $id");

        return $this->redirect(Yii::$app->request->referrer);

    }


    public function actionUpdateAllRelated()
    {
        $request = Yii::$app->request;
        $user = (!empty($request->post('yandex-name'))) ? $request->post('yandex-name') : null;
        $key = (!empty($request->post('yandex-key'))) ? $request->post('yandex-key') : null;

        if (!$user || $key) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        FilesHelper::getLastRelatedArticlesFullUpdateTime(true);
        $sitePath = Yii::getAlias('@sitePath');
        exec("php $sitePath/yii global-related-yandex 0 0 $user $key > /dev/null 2>/dev/null &");

    }

    public function actionChangeColumnsConfig()
    {

        $post = Yii::$app->request->post('ArticleSearch');
        if (!empty($post['checkboxes'])) {
            $checkBoxses = $post['checkboxes'];

            ShowGridColumn::deleteAll(['grid_id' => 1]);
            foreach ($checkBoxses as $attribute => $isChecked) {
                $showColumnModel = new ShowGridColumn();
                $showColumnModel->grid_id = 1;
                $showColumnModel->attribute = $attribute;
                $showColumnModel->is_checked = $isChecked;
                $showColumnModel->save();
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPopular()
    {
        $popular = CommonData::value('popular_articles');

        $popularArticle = Yii::$app->request->post('popular_article');
        if (isset($popularArticle)) {
            $popular = $popularArticle;
            CommonData::value('popular_articles', $popularArticle);
        }

        return $this->render('_popular', compact('popular'));
    }

    public function actionSendYandexOriginal($article_id)
    {
        $article = $this->findModel($article_id);
        $configs = Yii::$app->params['yandex'];

        if(empty($configs['user_id']) || empty($configs['host_id'])) {
            $this->console->output('Не установлен user_id или host_id');
            return false;
        }

        $client = new Client();

        $headers = [
                'Authorization' => 'OAuth ' . $configs['yandex_oauth_token'],
                'Content-type' => 'application/json',
            ];


        $content = strip_tags($article->content);
        $content = preg_replace('/\[(.+?)\]/', '', $content);
        $content = str_replace(["\n", "\t", "\r"], '', $content);
        $body = \GuzzleHttp\json_encode(['content' => $content], JSON_UNESCAPED_UNICODE);

        $response = $client->post('https://api.webmaster.yandex.net/v4/user/' . $configs['user_id'] . '/hosts/' . $configs['host_id'] . '/original-texts/', ['body' =>  $body])->addHeaders($headers)->send();

        return \GuzzleHttp\json_encode(['content' => $response->content, 'statusCode' => $response->statusCode]);
    }

    public function actionClearCache($article_id)
    {
        return AireeHelper::clearCache($article_id);
    }

    public function actionExport()
    {
        $articleIds = Yii::$app->request->get('articles', []);

        if(Yii::$app->request->get('ArticleSearch', [])) {
            $searchModel = new ArticleSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination = false;
            $articleIds = $dataProvider->getKeys();
        }

        if (empty($articleIds)) {
            Yii::$app->session->setFlash('danger', "Пожалуйста выберите статьи");
            return $this->redirect(['/article']);
        }

        $article = new Article();
        $articleMeta = new ArticleMeta();

        // click to export
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            // labels
            $labelKeyword = [];
            $labels = $article->attributeLabels();
            foreach ($post['Article'] as $column => $value) {
                if (empty($value)) {
                    continue;
                }
                $labelKeyword[$column] = $labels[$column];
            }
            $labels = $articleMeta->attributeLabels();
            foreach ($post['ArticleMeta'] as $column => $value) {
                if (empty($value)) {
                    continue;
                }
                $labelKeyword[$column] = $labels[$column];
            }

            $exportData = [];
            foreach ($post['articles'] as $articleId) {
                $article = Article::findOne($articleId);

                $articleData = [];
                foreach ($labelKeyword as $column => $label) {
                    if (isset($post['ArticleMeta'][$column])) {
                        $articleData[$column] = $article->articleMeta[$column];
                    } else {
                        $articleData[$column] = $article->{$column};

                        if ($column == 'slug') {
                            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? true;
                            $articleData[$column] = Url::to($article->url, $proto);
                        }
                    }
                }
                $exportData[] = $articleData;
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->fromArray($labelKeyword, NULL, 'A1');
            $sheet->fromArray($exportData, null, 'A3');

            foreach (range('A', 'G') as $column) {
                $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
            }

            $filename = 'article-export.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        $articlesUrl = '';
        if($articleIds) {
            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? true;
            $articlesUrl = [];
            $articles = Article::find()->select(['id', 'slug'])->where(['id' => $articleIds])->all();
            foreach ($articles as $articlee) {
                $articlesUrl[] = Url::to($articlee->url, $proto);
            }
            $articlesUrl = join("\n", $articlesUrl);
        }

        return $this->render('export', [
            'articles' => $articleIds,
            'articlesUrl' => $articlesUrl,
            'article' => $article,
            'articleMeta' => $articleMeta,
        ]);
    }

    /**
     * For select2 widget live search
     * @param null|string $search
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionSearchByTitle($search = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($search)) {
            $query = new Query();
            $query->select('id, title AS text')
                ->from('articles')
                ->where(['like', 'title', $search])
                ->limit(50);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
}
