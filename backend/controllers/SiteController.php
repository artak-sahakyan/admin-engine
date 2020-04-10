<?php
namespace backend\controllers;

use common\helpers\Api;
use common\helpers\FilesHelper;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'clear-assets', 'clear-cache', 'generate-yandex-token'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           // return $this->goBack();
            return $this->redirect('/admin/article');
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionClearAssets()
    {
        $dirPaths = [
            Yii::getAlias('@webroot') . '/assets',
            Yii::getAlias('@siteFrontend') . '/web/assets',
            Yii::getAlias('@siteBackend') . '/web/assets'
        ];

        foreach ($dirPaths as $path) {
            FilesHelper::deleteDirectoryByPath($path);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClearCache()
    {
        if(Yii::$app->cache) Yii::$app->cache->flush();

        $dirPaths = [
            Yii::getAlias('@webroot') . '/assets',
            Yii::getAlias('@siteFrontend') . '/web/assets',
            Yii::getAlias('@siteBackend') . '/web/assets'
        ];

        foreach ($dirPaths as $path) {
            FilesHelper::deleteDirectoryByPath($path);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionGenerateYandexToken() {

        $request = Yii::$app->request;
        $configs = Yii::$app->params['yandex'];

        if(!empty($request->get('add-user'))) {

            $api = new Api($configs);
            $userId = $api->getUserId();

            if(!empty($userId->user_id)) {
                $configs['yandex_oauth_client_id'] = $userId->user_id;
                FilesHelper::setConfigs('yandex', $configs);//Исправить
            }

            return $this->redirect('generate-yandex-token');
        }


        $clientID = $configs['yandex_oauth_client_id'];

        return $this->render('generateToken', compact('clientID'));
    }
}
