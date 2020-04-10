<?php
namespace backend\models;

use backend\interfaces\SearchInterface;
use common\helpers\{
    EditableHelper, 
    ArrayHelper,
    CategoryHelper
};
use common\models\{
    ArticleCategory,
    ArticleMeta,
    ArticleRelatedYandex,
    BannerGroup,
    Expert,
    Admin,
    ShowGridColumn
};
use kartik\daterange\DateRangePicker;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\{ 
    ActiveQuery,
    Expression,
    Query
};

/**
 * ArticleSearch represents the model behind the search form of `common\models\Article`.
 */
class ArticleSearch extends Article implements SearchInterface
{
    const GRID_ID = 'article-grid';
    public $unpublished_articles = 0;
    // public $banner_group;
    public $relatedArticles;
    public $updated_related;
    public $showLessThen20;
    public $unique_users_yesterday_count;
    public $pageSize;

    private static $experts = null;
    private static $publishers = null;
    private static $posters = null;
    private static $categories = null;
    private static $childrenCategories = null;
    private static $bannerGroups = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'banner_group_id', 'admin_id', 'created_at', 'updated_at', 'is_published', 'is_turbopage', 'send_zen'], 'integer'],
            [
                ['title', 'slug', 'description', 'breadcrumbs', 'content', 'image_extension',
                    'is_ready_for_publish', 'ready_publish_date', 'imported_at', 'anounce_end_date', 'yandex_origin_date', 'checked_anounce_end',
                    'published_at', 'show_banners', 'visit_counter', 'publisher_id','expert_id', 'admin_id', 'expert_id',
                    'bytextId', 'visits_last_day', 'noindex', 'main_query', 'is_turbopage', 'send_zen', 'relatedArticles', 'updated_related', 'showLessThen20',
                    'unique_users_yesterday_count', 'pageSize'
                ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find();

        if(\Yii::$app->user->identity->showOnlyOwnPosts()) {
            $query = $query->where(['or',
                ['admin_id' => \Yii::$app->user->id],
                ['publisher_id' => \Yii::$app->user->id]
             ]);
        }

        if($this->unpublished_articles) {
            $query->andWhere(['or', ['is_published' => 0], ['>', 'published_at', time()+ 60 * 60 * 3]]);
        } else {
            $query->andWhere(['is_published' => 1]);
            $query->andWhere(['<', 'published_at', time()+ 60 * 60 * 3]);
        }

        // add conditions that should always apply here
        $query->joinWith(['bannerGroup', 'articleMeta']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
//            'pagination' => [
//                'pageSize' => 20,
//            ]
        ]);

        $dataProvider->sort->attributes['banner_group'] = [
            'asc' => ['banner_group.name' => SORT_ASC],
            'desc' => ['banner_group.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unique_users_yesterday_count'] = [
            'asc' => [ArticleMeta::tableName() . '.unique_users_yesterday_count' => SORT_ASC],
            'desc' => [ArticleMeta::tableName() . '.unique_users_yesterday_count' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $table = static::tableName();

        //echo $this->updated_related;die;
        if (!is_null($this->updated_related) && strpos($this->updated_related, ' to ') !== false ) {
            list($start_date, $end_date) = explode(' to ', $this->updated_related);
            $query->joinWith('relatedYandexArticles')->andFilterWhere(['between', ArticleRelatedYandex::tableName() . '.updated_at', strtotime($start_date), strtotime($end_date) + (24 * 60 * 60)-1]);
            // echo $query->createCommand()->getRawSql();die;
        }

        if($this->pageSize) {
            $dataProvider->pagination->pageSize = $this->pageSize;
        }

        if ($this->relatedArticles) {

            $query->select($table . '.*');
            $query->addSelect(new Expression('COUNT(*) as cnt'));
            $query->innerJoinWith(['relatedYandexArticles' => function(ActiveQuery $q) {
                $q
                    ->groupBy(ArticleRelatedYandex::tableName().'.article_id')
                    ->having(new Expression('COUNT(*) = '.$this->relatedArticles));
            }]);
        }


        if($this->category_id) {

            $categories = ArticleCategory::find()->with(['childs' => function(ActiveQuery $q) {
                $q->with(['childs' => function(ActiveQuery $q) {
                    $q->indexBy('id');
                }])->indexBy('id');
            }])->andWhere(['parent_id' => $this->category_id])
                ->indexBy('id')
                ->all();

            $categoriesIds = [$this->category_id];

            foreach ($categories as $category) {
                $categoriesIds[] = $category->id;
                $categoriesIds = array_merge($categoriesIds, array_keys($category->childs));

                foreach ($category->childs as $childCategory) {
                    $categoriesIds = array_merge($categoriesIds, array_keys($childCategory->childs));
                }
            }

            $query->andFilterWhere([ $table.'.category_id' => $categoriesIds]);

           // echo $query->createCommand()->getRawSql(); die;

        }

        // grid filtering conditions
        $query->andFilterWhere([
            $table.'.id'                    => $this->id,
            $table.'.admin_id'              => $this->admin_id,
            $table.'.created_at'            => $this->created_at,
            $table.'.updated_at'            => $this->updated_at,
            $table.'.is_published'          => $this->is_published,
            $table.'.is_ready_for_publish'  => $this->is_ready_for_publish,
            $table.'.imported_at'           => $this->imported_at,
            $table.'.anounce_end_date'      => $this->anounce_end_date,
            $table.'.yandex_origin_date'    => $this->yandex_origin_date,
            $table.'.checked_anounce_end'   => $this->checked_anounce_end,
            $table.'.show_banners'          => $this->show_banners,
            $table.'.visit_counter'         => $this->visit_counter,
            $table.'.publisher_id'          => $this->publisher_id,
            $table.'.expert_id'             => $this->expert_id,
            $table.'.bytextId'              => $this->bytextId,
            $table.'.visits_last_day'       => $this->visits_last_day,
            $table.'.noindex'               => $this->noindex,
            $table.'.is_turbopage'          => $this->is_turbopage,
            $table.'.send_zen'              => $this->send_zen,
            $table.'.banner_group_id'       => $this->banner_group_id
        ]);

        $query->andFilterWhere(['like', $table.'.title', $this->title])
            ->andFilterWhere(['like', $table.'.slug', $this->slug])
            ->andFilterWhere(['like', $table.'.description', $this->description])
            ->andFilterWhere(['like', $table.'.breadcrumbs', $this->breadcrumbs])
//            ->andFilterWhere(['like', $table.'.meta_keywords', $this->meta_keywords])
//            ->andFilterWhere(['like', $table.'.meta_description', $this->meta_description])
//            ->andFilterWhere(['like', $table.'.meta_title', $this->meta_title])
            ->andFilterWhere(['like', $table.'.content', $this->content])
            ->andFilterWhere(['like', $table.'.image_extension', $this->image_extension])
            ->andFilterWhere(['like', $table.'.main_query', $this->main_query]);



            // $query->andFilterWhere(['like', 'banner_groups.id', $this->banner_group]);


            if($this->unique_users_yesterday_count) {
//                $query->joinWith('articleMeta');
                $query->andFilterWhere([ArticleMeta::tableName() . '.unique_users_yesterday_count' => $this->unique_users_yesterday_count]);
            }

        if($this->showLessThen20) {
            $ids = ArrayHelper::getColumn($this->getLessThenArticles(20, false), 'article_id');
            $query->andFilterWhere(['IN', $table. '.id', $ids]);
        }

        if (!is_null($this->ready_publish_date) && strpos($this->ready_publish_date, ' to ') !== false ) {
            list($start_date, $end_date) = explode(' to ', $this->ready_publish_date);
            $query->andFilterWhere(['between', $table . '.ready_publish_date', strtotime($start_date), strtotime($end_date) + (24 * 60 * 60)-1]);
           // echo $query->createCommand()->getRawSql();die;

        }

        if (!is_null($this->published_at) && strpos($this->published_at, ' to ') !== false ) {
            list($start_date, $end_date) = explode(' to ', $this->published_at);
            $query->andFilterWhere(['between', $table . '.published_at', strtotime($start_date), strtotime($end_date) + (24 * 60 * 60) - 1]);

        }

        return $dataProvider;
    }

    public function getAdsProperties()
    {
        return [1 => 'Да', 0 => 'Нет'];
    }

    public function publishedArticlesCount()
    {
        return Article::find()->where(['AND',['is_published' => 1], ['>=', 'published_at', time() + 60 * 60 * 3]])->count();
    }

    public function unPublishedArticlesCount()
    {
        return Article::find()->where(['AND',['is_published' => 1], ['<', 'published_at', time() + 60 * 60 * 3]])->count();
    }

    public function getLessThenArticles($count, $showCount = true) {
        $articleIds =  ArticleRelatedYandex::find()->select('article_id, COUNT(article_id) as cnt')->groupBy('article_id')->having(['<', 'cnt' , $count]);
        return $showCount ? $articleIds->count() : $articleIds->all();
    }

    public function getColumns($onlyColumns = false) {
        $showColumns = [];
        $columns = [];
     
        $columns = ShowGridColumn::find()->select(['is_checked', 'attribute'])->where(['grid_id' => 1])->indexBy('attribute')->column();

        if($onlyColumns) {
            return $columns;
        }

        if($columns) {
            foreach ($columns as $attribute => $is_checked) {

                if($is_checked && $this->allColumns($attribute)) {
                    $showColumns[] = $this->allColumns($attribute);
                }
            }
        }

        $showColumns[] = [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'header' => 'Управления',
        ];
        return $showColumns;
    }

    public function allColumns($attribute = false)
    {
        $allColumns =  [
            'checkboxColumn' => ['class' => 'yii\grid\CheckboxColumn'],
            'id' => [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:2%'],
            ],
            'image' => [
                'attribute' => 'image',
                'label' => 'Pic',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<img width="50"  src="' . $model->getThumb(50, 50) . '" >';
                },

            ],
            'title' => [
                'attribute' => 'title',
                'format' => 'raw',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'style' => 'width:100%',
                ],
                'contentOptions' => ['style' => 'min-width:350px; white-space: normal;'],
                'value' => function ($model) {
                    return $model->getLink(true);
                },
            ],
            'category_id' => [
                'attribute' => 'category_id',
                'enableSorting' => false,
                'filter' => $this->getAllCategories(),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id' => 'category_id'
                ],
                'content' => function($model)  {
                    $attribute = 'category_id';
                    $relation = 'category.title';

                    return EditableHelper::dropdown($model, $attribute, $relation, $this->getAllCategories());
                },
            ],
            'banner_group' => [
                'attribute' => 'banner_group_id',
                'filter' => $this->getBannerGroups(),
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id' => 'banner_group_id'
                ],
                'content' => function($model)  {
                    return EditableHelper::dropdown($model, 'banner_group_id', 'bannerGroup.name', $this->getBannerGroups());
                },
            ],
            'show_banners' => [
                'attribute' => 'show_banners',
                'filter' => $this->getAdsProperties(),
                'content' => function ($model) {
                    return EditableHelper::checkbox($model, 'show_banners', $this->getAdsProperties());
                }

            ],
            'unique_users_yesterday_count' => [
                'attribute' => 'unique_users_yesterday_count',
                'label' => 'Визиты',
                'value' => function ($model) {
                    return ($model->articleMeta) ? $model->articleMeta->unique_users_yesterday_count : null;
                },
            ],
            'ready_publish_date' => [
                'attribute' => 'ready_publish_date',
                'label' => 'Готовность',
                'value' => function ($model) {
                    return ($model->ready_publish_date) ? date('Y-m-d', $model->ready_publish_date) : null;
                },
                'filter' => '<div class="input-group drp-container">' . DateRangePicker::widget([
                        'name' => 'date_range_1',
                        'id' => 'date_range_1',
                        // 'value'=>'',
                        'model' => $this,
                        'attribute' => 'ready_publish_date',
                        'convertFormat' => true,
                        'useWithAddon' => false,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => ' to ',
                            ],
                            'opens' => 'left'
                        ],
                        'pluginEvents' => [
                            'cancel.daterangepicker' => "function(ev, picker) {\$('#articlesearch-ready_publish_date').val('');$('#article-grid').yiiGridView('applyFilter'); }"
                        ]
                    ]) . '<i style="padding: 10px 1px;font-size: 15px" class="fas fa-calendar-alt"></i></div>'

            ],
            'published_at' => [
                'attribute' => 'published_at',
                'label' => 'Публикация',
                'value' => function ($model) {
                    return ($model->published_at) ? date('Y-m-d', $model->published_at) : null;
                },
                'filter' => '<div class="input-group drp-container">' . DateRangePicker::widget([
                        'name' => 'date_range_2',
                        'id' => 'date_range_2',
                        // 'value'=>'',
                        'model' => $this,
                        'attribute' => 'published_at',
                        'convertFormat' => true,
                        'useWithAddon' => false,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => ' to ',
                            ],
                            'opens' => 'left'
                        ],
                        'pluginEvents' => [
                            'cancel.daterangepicker' => "function(ev, picker) {\$('#articlesearch-published_at').val('');$('#article-grid').yiiGridView('applyFilter'); }"
                        ]
                    ]) . '<i style="padding: 10px 1px;font-size: 15px" class="fas fa-calendar-alt"></i></div>'

            ],
            'admin_id' => [
                'attribute' => 'admin_id',
                'label' => 'Постер',
                'filter' => $this->getPosters(),
                'content' => function($model)  {
                    if(\Yii::$app->user->identity->allowedChangePublisherAndPoster()) {
                        $res = EditableHelper::dropdown($model, 'admin_id', 'admin.username',  $this->getPosters());
                    } else {
                        $res = $model->admin->username ?? '-';
                    }
                    return $res;
                }
            ],
            'publisher_id' => [
                'attribute' => 'publisher_id',
                'label' => 'Публицист',
                'filter' => $this->getPublishers(),
                'content' => function($model)  {
                    if(\Yii::$app->user->identity->allowedChangePublisherAndPoster()) {
                        $res = EditableHelper::dropdown($model, 'publisher_id', 'publisher.username',  $this->getPublishers());
                    } else {
                        $res = isset($model->publisher->username) ? $model->publisher->username : '-';
                    }
                    return $res;
                }
            ],
            'expert_id' => [
                'attribute' => 'expert_id',
                'filter' => $this->getExperts(),
                'content' => function($model)  {
                    return EditableHelper::dropdown($model, 'expert_id', 'expert.username', $this->getExperts());
                }
            ],
        ];

        return ($attribute) ? ((!empty($allColumns[$attribute])) ? $allColumns[$attribute] : false) : $allColumns;
    }

    public function getCheckboxes() {
        return \yii\helpers\ArrayHelper::map(ShowGridColumn::find()->where(['grid_id' => 1])->all(), 'attribute', 'is_checked');
    }

    public function getExperts() {
        if(!self::$experts) self::$experts = Expert::find()->select(['username', 'id'])->where(['is_expert' => 1])->indexBy('id')->column();
        return self::$experts;
    }

    public function getPublishers() {
        if(!self::$publishers) self::$publishers = Admin::getEmployeeList(Admin::PUBLISHER);
        return self::$publishers;
    }

    public function getPosters() {
        if(!self::$posters) self::$posters = Admin::getEmployeeList(Admin::POSTER);
        return self::$posters;
    }


    public function getChildrenCategories() {
        if(!self::$childrenCategories) self::$childrenCategories = ArticleCategory::getChildsCategoriesList($this->category_id);
        return self::$childrenCategories;
    }

    public function getBannerGroups() {
        if(!self::$bannerGroups) self::$bannerGroups = ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name');
        return self::$bannerGroups;
    }

    public function getAllCategories() {
        if(!self::$categories) self::$categories = ArrayHelper::map(CategoryHelper::getCatogiriesForDropDownm(ArticleCategory::find()->asArray()->all(), '-'), 'id', 'title');
        return self::$categories;
    }


}
