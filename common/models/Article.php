<?php

namespace common\models;

use common\behaviors\{
    ImageBehavior,
    InsertBannerShortcodeBehavior,
    ContentProcessBehavior,
    ParserBehavior,
    PropImageBehavior,
    WordsSearcherBehavior,
    MiratextBehavior,
    UpdateLinksBehavior,
    ValidateArticleBehavior
};
use common\helpers\FilesHelper;
use common\models\scopes\ArticleQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\{ Html, Url, ArrayHelper };
use yii\db\Expression;
use yii\httpclient\Client;
use common\jobs\ArticleSaveJob;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property int $id
 * @property int $category_id
 * @property int $banner_group_id
 * @property int $admin_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $breadcrumbs
 * @property string $content
 * @property string $image_extension
 * @property array $image_color
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_published
 * @property int $published_at
 * @property int $is_ready_for_publish
 * @property int $ready_publish_date
 * @property int $imported_at
 * @property int $anounce_end_date
 * @property int $yandex_origin_date
 * @property int $checked_anounce_end
 * @property int $show_banners
 * @property int $visit_counter
 * @property int $expert_id
 * @property int $publisher_id
 * @property int $bytextId
 * @property int $visits_last_day
 * @property int $noindex
 * @property string $main_query
 * @property int $is_turbopage
 * @property int $bannerGroup
 * @property int $dzen
 * @property int $is_actual
 * @property int $is_fix_sidebar
 * @property int $is_double_banner_place_manual_fix
 *
 * @property int $parent_category_id
 * @property int $child_category_id
 * @property int $subchild_category_id
 *
 * @property Admin $admin
 * @property ArticleCategory $category
 * @property NauseaOfArticle $nauseaOfArticle
 * @property ArticleMeta $articleMeta
 * @property Article[] $relatedArticles

 */

class Article extends \yii\db\ActiveRecord
{

    public $imageFile;

    public $displayContent;

    public $parent_category_id;
    public $child_category_id;
    public $subchild_category_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%articles}}';
    }

    public function behaviors()
    {
        return [
            InsertBannerShortcodeBehavior::class,
            ContentProcessBehavior::class,
            UpdateLinksBehavior::class,
            TimestampBehavior::class,
            ParserBehavior::class,
            WordsSearcherBehavior::class,
            ValidateArticleBehavior::class,
            [
                'class' => ImageBehavior::class,
                'previewPath' => '/photos/articles/'
            ],
            PropImageBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'main_query',
                'slugAttribute' => 'slug',
            ],
            MiratextBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'banner_group_id', 'admin_id', 'created_at', 'updated_at', 'is_published', 'visit_counter', 'bytextId', 'visits_last_day', 'noindex', 'is_turbopage', 'send_zen', 'airee_clear_cache_date', 'is_actual', 'is_fix_sidebar'], 'integer'],
            [['title'], 'required'],
            [['category_id'], 'required', 'when' => function($model){
                return !empty($model->is_published);
            }],
            [
                // required at least one from three field
                ['parent_category_id', 'child_category_id', 'subchild_category_id'],
                'required',
                'when' => function($model){
                    $validate = false;
                    if (!empty($model->is_published)) {
                        $validate = empty($model->parent_category_id) && empty($model->child_category_id) && empty($model->subchild_category_id);
                    }
                    return $validate;
                },
            ],
            [['description', 'content', 'main_query'], 'string'],
            [['title', 'slug', 'breadcrumbs', 'image_extension'], 'string', 'max' => 255],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::class, 'targetAttribute' => ['admin_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['image_color'], 'default', 'value' => 'null'],
            [['is_double_banner_place_manual_fix'], 'default', 'value' => 0],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            [['banner_group_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => true, 'targetClass' => BannerGroup::class, 'targetAttribute' => ['banner_group_id' => 'id']],
            ['content', 'validateContent'],
            [
                [
                    'published_at', 'ready_publish_date', 'is_ready_for_publish', 'imported_at', 'anounce_end_date', 'yandex_origin_date',
                    'checked_anounce_end', 'show_banners', 'visit_counter', 'expert_id', 'publisher_id', 'admin_id', 'bannerGroups',
                    'dzen', 'airee_clear_cache_date', 'is_double_banner_place_manual_fix',
                ],
                'safe'
            ],
            [['parent_category_id', 'child_category_id', 'subchild_category_id', 'head_text'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Kатегория',
            'admin_id' => 'Постер',
            'title' => 'Заголовок (H1)',
            'slug' => 'URL',
            'description' => 'Description',
            'breadcrumbs' => 'Заголовок для хлебных крошек',
            'content' => 'Текст статьи',
            'image_extension' => 'Превью статьи',
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
            'is_published' => 'Опубликована',
            'published_at' => 'Публикация',
            'is_ready_for_publish' => 'Гoтовa',
            'ready_publish_date' => 'Готовность',
            'imported_at' => 'Импортирована',
            'anounce_end_date' => 'Анонс до',
            'yandex_origin_date' => 'Yandex Origin',
            'checked_anounce_end' => 'Анонс до',
            'show_banners' => 'Ads',
            'visit_counter' => 'Визиты',
            'publisher_id' => 'Публицист',
            'expert_id' => 'Эксперт',
            'bytextId' => 'Bytext ID',
            'visits_last_day' => '',
            'noindex' => 'meta noindex',
            'main_query' => 'Основной запрос',
            'is_turbopage' => 'Турбостраница',
            'send_zen' => 'Отправка в Zen',
            'dzen' => 'Dzen',
            'unique_users_yesterday_count' => 'Визиты',
            'bannerGroups' => 'Баннерная группа',
            'publisher_id' => 'Публицист',
            'image' => 'Pic',
            'banner_group_id' => 'Группа',
            'is_actual' => 'Актуально',
            'is_fix_sidebar' => 'Закреплен в сайдбаре',
            'is_double_banner_place_manual_fix' => 'Ручная правка дублирования баннеров',
            'parent_category_id' => 'Родительская категория',
            'child_category_id' => 'Подкатегория (2 уровень)',
            'subchild_category_id' => 'Подкатегория (3 уровень)',
            'head_text' => 'Текст в загаловке страницы (head)'

        ];
    }

    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }


    public function validateContent($attribute, $params, $validator)
    {
        if (preg_match("/<(h2|h3)>(.+)?<\/?.+>(.+)?<\/(h2|h3)>/i", $this->$attribute, $match)) {
            $this->addError($attribute, 'h2, h3 не должны содержать другие теги');
        }
    }

    public function timeFormatForDatePicker($attribute) {
        $time = null;
        if($this->$attribute) {
            $time = (is_numeric($this->$attribute)) ? date('Y-m-d H:i', $this->$attribute) : $this->$attribute;
        }

        $this->$attribute = $time;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::class, ['id' => 'admin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublisher()
    {
        return $this->hasOne(Admin::class, ['id' => 'publisher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::class, ['id' => 'category_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(Expert::class, ['id' => 'expert_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNauseaOfArticle()
    {
        return $this->hasOne(NauseaOfArticle::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHtmlErrors()
    {
        return $this->hasOne(ArticleHtmlError::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleNavigation()
    {
        return $this->hasOne(ArticleNavigation::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleMeta() {
        return $this->hasOne(ArticleMeta::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleSocial() {
        return $this->hasOne(ArticleSocial::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingVsArticle()
    {
        return $this->hasMany(VotingLink::className(), ['link_id' => 'id'])->andOnCondition(['morph' => VotingLink::ARTICLE]);
    }

    public function getVotings() {
        return $this->hasMany(Voting::class, ['id' => 'voting_id'])
          ->via('votingVsArticle')->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedYandexArticles()
    {
        return $this->hasMany(ArticleRelatedYandex::class, ['article_id' => 'id'])->orderBy([ArticleRelatedYandex::tableName() . '.id' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedArticles()
    {
        $sort_ids = [];
        foreach ($this->relatedYandexArticles as $k => $relatedArticles)
            $sort_ids[] = $relatedArticles->related_article_id;

        return $this->hasMany(self::class, ['id' => 'related_article_id'])
            ->via('relatedYandexArticles')->orderBy([new Expression('FIELD (id, ' . implode(',', $sort_ids) . ')')]);
    }

    /**
     * @return ArticleMeta
     */
    public function checkOrGetMeta()
    {
        $initData = $this->isNewRecord ? [] : ['article_id' => $this->id];
        return $this->articleMeta ? $this->articleMeta : new ArticleMeta($initData);
    }

    /**
     * @return ArticleMeta
     */
    public function checkOrGetBannerGroup()
    {
        $initData = $this->isNewRecord ? [] : ['id' => $this->banner_group_id];
        return $this->bannerGroup ? $this->bannerGroup : new BannerGroup($initData);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $slug = "/{$this->id}-{$this->slug}.html";
        return Url::to($slug);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerGroup()
    {
        return $this->hasOne(BannerGroup::class, ['id' => 'banner_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleRating()
    {
        return $this->hasOne(ArticleRating::class, ['id' => 'article_id']);
    }

    /**
     * @param null $options
     * @return string
     */
    public function getLink($options = null)
    {
        return Html::a($this->title, $this->getUrl(), ($options) ? ['target' => '_blank'] : []);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAnchor()
    {
        return $this->hasMany(ArticleAnchor::class, ['article_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['article_id' => 'id'])->where(['visible' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAdvertisingIntegrations() {
        return $this->hasMany(ArticleAdvertisingIntegration::class, ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleErrorInfo()
    {
        return $this->hasOne(ArticleErrorInfo::class, ['article_id' => 'id']);
    }

    public function getContentWithoutBanners()
    {
        return $this->contentProcessors([
            'votings'   => false,
            'turbo'     => true,
            'banners'   => false,
            'related'   => true
        ])->displayContent;
    }

    public function getContentWithoutNavigation()
    {
        $content = $this->contentProcessors([
            'navigation' => false,
            'markup'    => false,
            'votings'   => false,
            'videos'    => false,
            'related'   => false,
            'turbo'     => false,
            'amp'       => false,
            'banners'   => false
        ])->displayContent;

        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
        $content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
        $content = preg_replace('/style=\"[^\"]*\"/','',$content);
        $content = preg_replace('/class=\"[^\"]*\"/','',$content);
        $content = preg_replace('#<a name=\"h(.*?)*\"></a>#is','',$content);
        $content = str_replace('<h2>Видео</h2>', '', $content);
        $content = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $content);
        $content = str_replace(['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'blockquote', 'strong', 'ul', 'ol', 'li'], 'p', $content);

        return $content;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                self::deleteBannerShortcodes();
                self::insertBannerShortcodes();
                self::insertTurboBannerShortcodes();
                self::setDopBannerShortcodes();
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->queue->push(new ArticleSaveJob([
            'article_id' => $this->id,
            'changedAttributes' => $changedAttributes
        ]));

        if($this->is_fix_sidebar)
            $this->setFixSidebar();

        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * Отправление новых статей в переобход яндекса
     * @param array $changedAttributes (keys isset if change attribute)
     * api doc => https://yandex.ru/dev/webmaster/doc/dg/reference/host-recrawl-post-docpage/
     * @return bool
     */
    public function sendYandexRecrawl($changedAttributes) {

        if(isset($changedAttributes['is_published']) && $changedAttributes['is_published'] == 0 && $this->is_published) {
            $article = self::findOne($this->id);
            $configs = Yii::$app->params['yandex'];

            $client = new Client();

            $headers = [
                'Authorization' => 'OAuth ' . $configs['token'],
                'Content-type' => 'application/json',
            ];


            $response = $client->post('https://api.webmaster.yandex.net/v4/user/' . $configs['user_id'] . '/hosts/' . $configs['host_id'] . '/recrawl/queue/', ['url' => Yii::$app->urlManager->hostInfo . $article->getUrl()])->addHeaders($headers)->send();


//           echo "<pre>";
//           print_r($response->content);
//           echo "</pre>";die;
        }
    }

    public function setCategoryId()
    {
        if ($this->subchild_category_id) {
            $this->category_id = $this->subchild_category_id;
        } elseif ($this->child_category_id) {
            $this->category_id = $this->child_category_id;
        } elseif ($this->parent_category_id) {
            $this->category_id = $this->parent_category_id;
        } else {
            $this->category_id = null;
        }

        return $this->category_id;
    }

    public function loadCategoryLevels($categoryId)
    {
        if(!$categoryId) return;

        $category = ArticleCategory::find()->where(['id' => $categoryId])->with('parents')->indexBy('id') ->one();

        $categories = [$category->id];

        if($category->parent_id) $categories[] = $category->parent_id;
        if(!empty($category->parents[0]->parent_id)) $categories[] = $category->parents[0]->parent_id;

        $levels = count($categories);

        if ($levels == 1) {
            $this->parent_category_id = $categories[0];
            return;
        }

        if ($levels == 2) {
            $this->child_category_id = $categories[0];
            $this->parent_category_id = $categories[1];

            return;
        }

        if ($levels == 3) {
            $this->subchild_category_id = $categories[0];
            $this->child_category_id = $categories[1];
            $this->parent_category_id = $categories[2];

            return;
        }
    }

    /**
     * Anchor or article title
     *
     * @return string
     */
    public function getAnchorTitle()
    {
        return $this->articleAnchor[0]->title ?? $this->title;
    }

    public function changeId(int $newId)
    {
        $article = Article::find()->where(['id' => $newId])->one();
        if($article) {
            throw new \Exception("Невозможно сменить ID. Такая статья уже существует", $newId);
        }

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SET FOREIGN_KEY_CHECKS=0;
            SET @from = :from, @to = :to;
            UPDATE `articles_related_yandex` SET article_id = @to WHERE article_id = @from;
            UPDATE `article_advertising_integrations` SET article_id = @to WHERE article_id = @from;
            UPDATE `article_anchors` SET article_id = @to WHERE article_id = @from;
            UPDATE `article_metas` SET article_id = @to WHERE article_id = @from;
            UPDATE `article_navigation` SET article_id = @to WHERE article_id = @from;
            UPDATE `articles` SET id = @to WHERE id = @from;", [':from' => $this->id, ':to' => $newId]);

        $result = $command->execute();

        $this->id = $newId;
        return $newId;
    }

    private function setFixSidebar()
    {
        Article::updateAll(['is_fix_sidebar' => false], ['<>', 'id', $this->id]);
    }
}
