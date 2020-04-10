<?php
namespace frontend\controllers;

use Yii;
use common\widgets\{ 
    BannerWidget
};
use common\behaviors\{
    AvatarBehavior, 
    ContentProcessBehavior, 
    GetBannersBehavior
};
use common\helpers\{
    ArrayHelper, ResponseHelper, SeoHelper
};
use common\models\{
    Article, ArticleCategory, BannerDevice, Email
};
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\{
    Controller, GoneHttpException, NotFoundHttpException
};
use yii\data\Pagination;
use Detection\MobileDetect;

class HomeController extends Controller
{
    const ERROR_PAGE_ARTICLES_COUNT = 8;

    public function init() {
        $this->getDeviceId();
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
    }

    public function actionIndex()
    {
        

        $newArticles = Article::getDb()->cache(function ($db) {
           $moscowTime =  time() + 60 * 60 * 3;
            return Article::find()
                ->where(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', $moscowTime]])
                ->select(['id', 'title', 'slug', 'main_query', 'image_color', 'image_extension', 'content'])
                ->orderBy('published_at DESC')
                ->limit(Yii::$app->params['articles']['home_main_limit'])
                ->all();
        });

        $anonce = Article::getDb()->cache(function ($db) {
            $moscowTime =  time() + 60 * 60 * 3;
            return Article::find()
                ->where(['AND',['is_published' => 1], ['>', 'anounce_end_date', $moscowTime]])
                ->select(['id', 'title', 'slug', 'main_query', 'image_color', 'image_extension', 'content'])
                ->orderBy('published_at DESC')
                ->all();
        });

        $newestIds = ArrayHelper::getColumn($newArticles, 'id');

        $allCategories = ArticleCategory::getDb()->cache(function ($db) {
            return ArticleCategory::find()
                ->where(['IS', 'parent_id', null])
                ->with([
                    'childs' => function ($query) {
                        $query->select(['id', 'title', 'slug', 'parent_id']);
                    }
                ])
                ->orderBy('sort')
                ->select(['id', 'title', 'slug'])
                ->all();
        });
        
        $articlesByCategory = [];
        $moscowTime =  time() + 60 * 60 * 3;
        foreach ($allCategories as $category) {

            $articlesByCategory[$category->slug] = [
                'main' => [ 
                    'id'    => $category->id, 
                    'title' => $category->title, 
                    'slug'  => $category->slug
                ],
                'childs'    => array_slice($category->childs, 0, Yii::$app->params['categories']['subcategory_limit']),
                'articles'  => $category->getLastArticles()
                    ->andWhere([
                        'and', 
                        ['NOT IN', 'id', $newestIds], 
                        ['is_published' => 1],
                        ['is_actual' => 1],
                        ['<=', 'published_at', $moscowTime]
                    ])
                    ->limit(Yii::$app->params['articles']['by_category_article_limit'])
                    ->all()
            ];
        }

        $seo =  Yii::$app->params['metas'];
        return $this->render('index', compact('newArticles', 'articlesByCategory', 'seo', 'anonce'));
    }

    public function actionArticle($url, $ad = null) {
        ResponseHelper::checkAndRedirect(Yii::$app->request->url);

        $url = explode('-', $url);
        $id = array_shift($url);

        $article = Article::getDb()->cache(function ($db) use($id){
            $moscowTime = $this->moscowTime();
            return Article::find()->where(['id' => $id])->andWhere(['is_published' => 1])->andWhere(['<=', 'published_at', $moscowTime])->with('articleMeta', 'expert')->one();
        });

        if(isset($article->category)) {
            $category = (!$article->category->head_text && isset($article->category->parent)) ? $article->category->parent->head_text : $article->category->head_text;
            $this->view->params['head_text'] = ($category ?? $article->head_text) . $article->head_text;
        }

        if(!isset($id) || empty($article) || ($article->slug != implode('-', $url))) throw new NotFoundHttpException(404);

        $article->attachBehaviors([
            AvatarBehavior::class,
            ContentProcessBehavior::class
        ]);

        $seo = SeoHelper::setSeo($article->articleMeta, ['meta_title', 'meta_keywords', 'meta_description']);

        if($ad && $ad == 'amp') {
            if (!Yii::$app->params['articles']['amp_enable']) {
                $canonicalUrl = preg_replace('#\?.+#', '', Yii::$app->request->url);
                return $this->redirect($canonicalUrl, 302);
            }
            $article->contentProcessors([
                'votings'   => false,
                'banners'   => false,
                'related'   => true,
                'amp'       => true
            ]);
            $page = 'amp-article';
            $this->layout = 'amp';
            $this->view->params['article'] = $article;
        } else {
            $article->contentProcessors();
            $page = 'article';
            if (Yii::$app->params['articles']['lazyloading']) {
                $article->displayContent = $article->imageLazyLoading($article->displayContent);
            }
            $places = $this->getBanners($article);

        }

        return $this->render($page, compact('article', 'seo', 'places'));
    }

    public function actionCategory($categorySlug, $subSlug=null, $childSub=null, $page=1) {

        ResponseHelper::checkAndRedirect(Yii::$app->request->url);

        // check page number
        if(is_numeric($subSlug)) {
            $page = $subSlug;
            $subSlug = false;

            if(is_numeric($childSub)) {
                $page = $childSub;
                $childSub=false;
            }
        }

        $findDownCategory = ($subSlug) ? (($childSub) ? $childSub : $subSlug) : $categorySlug;

        $query = ArticleCategory::find()->where(['slug' => $findDownCategory]);

        $checkCategoryQuery = ($childSub) ? $query : $query->with('childs');

        $category = ArticleCategory::getDb()->cache(function ($db) use($checkCategoryQuery){
            return $checkCategoryQuery->one();
        });


        $checkCategory = ArticleCategory::getDb()->cache(function ($db) use($categorySlug, $subSlug, $childSub){
            return ArticleCategory::find()->where(['slug' => $categorySlug])->with(['child' => function(ActiveQuery $q) use($subSlug, $childSub) {
                $q->andFilterWhere(['slug' => $subSlug])->with(['child' => function(ActiveQuery $q) use($childSub) {
                    $q->andFilterWhere(['slug' => $childSub]);
                }]);
            }])->one();
        });


        if(
            !$checkCategory ||
            ($subSlug && !isset($checkCategory->child)) || ($childSub && !isset($checkCategory->child->child)) ||
            isset($checkCategory->parent_id)
        ) {

            throw new NotFoundHttpException(404);
        }

        $offset = ($page) ? ($page-1) * Yii::$app->params['categories']['category_page_limit'] : 0;

        $query =  $category->getLastArticles();

        $moscowTime = $this->moscowTime();
        $articles = $query->where(['AND', ['is_published' => 1], ['<=', 'published_at', $moscowTime]])->offset($offset)->limit(Yii::$app->params['categories']['category_page_limit'])->all();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => Yii::$app->params['categories']['category_page_limit'],
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSizeLimit' => false,
        ]);

        $headText = (empty($category['head_text']) && !empty($category->parent->head_text)) ? $category->parent->head_text : $category['head_text'];
        $this->view->params['head_text'] = $headText;

        $data = [
            'category' => $category,
            'articles' => $articles,
            'page'      => $page,
            'pages' => $pages,
            'isSubCategory' => $subSlug,
            'seo' => SeoHelper::setSeo($category, ['metaTitle', 'metaKeywords', 'metaDescription'])
        ];

        $data['places'] = self::getBanners();

        if($page > 1) {
            $data['meta'][] = ['name' => 'robots', 'content' => 'noindex, follow'];
        }

        return $this->render('category', $data);
    }

    public function actionAbout() {

        $command = json_decode(file_get_contents(\Yii::getAlias('@web') . 'team.json'), true);
        $seo = SeoHelper::aboutSeo();
        return $this->render('about', compact('command', 'seo'));
    }

    public function actionSearch() {
        return $this->render('search');
    }

    public function actionAdvertising() {
        $seo = SeoHelper::aboutAdvertising();
        return $this->render('advertising', compact('seo'));
    }

    public function actionSitemap($page = 1) {

        $activePage = $page > 0 ? (int)$page : 1;

        $perPage = \Yii::$app->params['sitemap_per_page'];

        $moscowTime = $this->moscowTime();
        $articles = Article::find()->select(['id', 'title', 'slug', 'category_id'])->where(['AND', ['is_published' => 1], ['<', 'published_at', $moscowTime]])->with('category')->orderBy('published_at DESC')->asArray();

        $count = (clone $articles)->count();

        // $articles = Article::getDb()->cache(function ($db) use($articles, $activePage, $perPage){
        //     return $articles->offset(($activePage-1) * $perPage)->limit($perPage)->all();
        // });

        $articles =  $articles->offset(($activePage-1) * $perPage)->limit($perPage)->all();

        if(!$articles) throw new NotFoundHttpException(404);

        $categories = ArticleCategory::getDb()->cache(function($db) {
            return ArticleCategory::find()->select(['id', 'title', 'slug'])->orderBy('sort')->indexBy('id')->asArray()->all();
        });

        $result = [];

        foreach ($articles as $article) {

            if(!$article['category']) continue;

            $categoryId = (!isset($article['category']['parent_id'])) ? $article['category_id'] : $article['category']['parent_id'];

            if(!isset($result[$categoryId]) && empty($result[$categoryId])) {
                $sub = $categories[$categoryId];
            } else {
                $sub = $result[$categoryId];
            }

            $sub['articles'][] = ['title' => $article['title'], 'url' => $article['id'] . '-' . $article['slug'] . '.html' ];
            $result[$article['category_id']] = $sub;
        }

        $sitemap = [];
        foreach (array_keys($categories) as  $key) {
            if(isset($result[$key]))  $sitemap[$key] = $result[$key];

        }

        $pages = new Pagination([
            'totalCount' => $count,
            'defaultPageSize' => $perPage,
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSizeLimit' => false,
        ]);
        $seo =  SeoHelper::siteMapSeo();
        return $this->render('sitemap', compact('sitemap', 'pages', 'seo'));

    }

    public function actionTerms() {
        return $this->render('terms', [
            'seo' => SeoHelper::termsSeo()
        ]);
    }

    public function actionError()
    {
        $error = (isset(Yii::$app->errorHandler->exception->statusCode)) ? Yii::$app->errorHandler->exception->statusCode : 404;

        $newArticles = Article::getDb()->cache(function($db){
            $moscowTime = $this->moscowTime();
            return Article::find()->where(['AND', ['is_published' => 1], ['<=', 'published_at', $moscowTime]])->select(['id', 'title', 'slug', 'image_color'])->orderBy('published_at DESC')->limit(self::ERROR_PAGE_ARTICLES_COUNT)->all();
        });
        return $this->render('error', ['newArticles' => $newArticles, 'code' => $error]);
    }
    
    protected function getDeviceId()
    {
        $detector = new MobileDetect();
        $deviceName = 'desktop';
        if( $detector->isTablet() ){
          $deviceName = 'tablet';
        }
        elseif ( $detector->isMobile() ) {
            $deviceName = 'mobile';
        }
        Yii::setAlias('@device_name', $deviceName);

        $device_id = BannerDevice::find()
            ->where(['alias' => $deviceName])
            ->select('id')
            ->column();
        Yii::setAlias('@device_id', $device_id[0]);
    }

    protected function getBanners($article = null)
    {
        $places = [];

        $bannerGroup = (isset($article)) ? $article->bannerGroup : null;

        $banners = GetBannersBehavior::getAllBanners($bannerGroup, $this->aliases);

        foreach ($this->aliases as $key => $alias) {
            foreach ($banners as $banner) {
                if($banner->place->alias == $alias) {
                    $places[$alias][] = BannerWidget::widget([
                        'banner'    => $banner,
                    ]);
                    break;
                }                    
            }
        }
        
        return $places;
    }

    /**
     * Return moscow time
     *
     * @return int
     */
    protected function moscowTime()
    {
        // UTC + 3 hours
        return time() + 60 * 60 * 3;
    }

    public function actionFeedback()
    {
        $model = new Email();
        $isSent = false;
        $errors = [];

        if(!empty(Yii::$app->request->get())) {

            $model->name = Yii::$app->request->get('name');
            $model->content = Yii::$app->request->get('content');
            $model->email = Yii::$app->request->get('email');
            $model->reCaptcha = Yii::$app->request->get('reCaptcha');

            $model->save();

            if($model->send())
            {
               $isSent = true;
               $model->save();
            } else {
                $errors[] = 'Ошибка отправки';
            }
        }

        return $this->render('feedback', compact('model', 'isSent', 'errors'));
    }
}
