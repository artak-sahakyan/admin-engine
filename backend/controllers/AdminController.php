<?php
namespace backend\controllers;

use backend\events\{ 
    ControllerBeforeRenderEvent,
    ControllerModelDeleteEvent,
    ControllerModelSaveEvent,
    ControllerUnpublishedEvent
};
use common\helpers\EditableHelper;
use yii\filters\{
    VerbFilter, 
    AccessControl 
};
use yii\base\{ 
    Event, 
    NotSupportedException 
};
use yii\helpers\{ 
    Url, 
    ArrayHelper 
};
use yii\web\{
    Controller, NotFoundHttpException
};
use backend\interfaces\SearchInterface;
use Yii;
use yii\db\ActiveRecord;


/**
 * Admin controller
 * @property ActiveRecord $modelClass
 */
abstract class AdminController extends Controller
{
    protected $modelClass;
    protected $searchClass;

    const MODEL_SAVE_EVENT = 'modelSave';
    const BEFORE_RENDER_EVENT = 'beforeRender';
    const MODEL_AFTER_SAVE_EVENT = 'afterModelSave';
    const MODEL_UNPUBLISHED = 'unpublishedArticles';
    const MODEL_AFTER_DELETE = 'afterModelDelete';
    protected $params = [];
    protected $data = [];
    protected $page = null;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ArticleCategory models.
     * @param bool $params
     * @return string
     * @throws NotSupportedException
     */
    public function actionIndex()
    {
        $searchClass = $this->searchClass ? $this->searchClass : str_replace('common', 'backend', $this->modelClass).'Search';
        $searchModel = new $searchClass();

        if(!$searchModel instanceof SearchInterface) {
            throw new NotSupportedException('Your search model should implement backend\interfaces\SearchInterface !');
        }

        $params = $this->params;
        if($params && is_array($params)) {
            foreach ($params as $attribute => $value) {
                $searchModel->{$attribute} = $value;
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render((!$this->page) ? 'index' : $this->page, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $this->data,
        ]);
    }

    /**
     * Creates a new ArticleCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new $this->modelClass();
        /* @var $model ActiveRecord */

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->trigger(static::MODEL_SAVE_EVENT, new ControllerModelSaveEvent(['model' => $model, 'insert' => true]));
            $this->trigger(static::MODEL_AFTER_SAVE_EVENT, new ControllerModelSaveEvent(['model' => $model, 'insert' => true, 'saved' => $model->save()]));
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $event = new ControllerBeforeRenderEvent(['args' => compact('model'), 'insert' => true]);
        $this->trigger(static::BEFORE_RENDER_EVENT, $event);
        return $this->render('create', $event->args);
    }

    /**
     * Updates an existing ArticleCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldData = $model->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $this->trigger(static::MODEL_SAVE_EVENT, new ControllerModelSaveEvent(['model' => $model, 'insert' => false]));
            $this->trigger(static::MODEL_AFTER_SAVE_EVENT, new ControllerModelSaveEvent(['model' => $model, 'insert' => false, 'saved' => $model->save(), 'oldData' => $oldData]));
            if(Yii::$app->request->post('apply')) {
                Yii::$app->cache->flush();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else {
                Yii::$app->cache->flush();
                return $this->redirect(['index']);
            }
        }
        $event = new ControllerBeforeRenderEvent(['args' => compact('model'), 'insert' => false]);
        $this->trigger(static::BEFORE_RENDER_EVENT, $event);

        return $this->render('update', $event->args);
    }


    /**
     * Deletes an existing ArticleCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        $this->trigger(static::MODEL_AFTER_DELETE, new ControllerModelDeleteEvent(['model' => $model]));
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = $this->modelClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays a single ArticleAdvertisingIntegration model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Ajax update value ArticleCategory model.
     * @param integer $id
     * @param string $relationName
     * @param json $returnedValues
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEditable($id, $relationName = null, $returnedValues = null, $mode = null, $className = null, $relationAttribute = null)
    {
        if (($model = $this->findModel($id)) == null) {
            throw new NotFoundHttpException('Model not found.');
        }

        $formName = $model->formName();
        $words = array_filter(preg_split('/(?=[A-Z])/',$formName));
        $formName = (count($words) > 1) ? join('-', $words) : $formName;

        if($formName == EditableHelper::ADMIN) $formName = EditableHelper::ADMIN_USER;

        $posted = Yii::$app->request->post($formName);
        $output = ['output' => null];

        $model->load($posted, '');
        $res = $model->save(false);

        if(!$res) {
            $output['message']  = 'error save';
        }
        elseif (empty($mode)) {

            if($relationName) {
                $output['output'] = (!empty($model->$relationName)) ? $model->$relationName->{($relationAttribute) ? $relationAttribute : 'name'} : 'Не установлено';
            } elseif($returnedValues) {
                $returnedValues = json_decode($returnedValues, true);
                $output['output'] = $returnedValues[current($posted)];
            } else {
                $output['output'] = current($posted);
            }

        } 
        else if(!empty($mode) && $mode == 'multiple') {
            $model->updateLinks($posted[$relationName], $relationName, $className);
            $output['output'] = implode(', ', ArrayHelper::map($model->$relationName, 'id', 'name'));
        }
        
        return json_encode($output);
    }

}
