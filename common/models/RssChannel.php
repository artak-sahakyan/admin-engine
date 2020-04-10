<?php
namespace common\models;

use common\helpers\FilesHelper;
use Yii;

/**
 * This is the model class for table "rss_channels".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $container_template
 * @property string $item_template
 * @property int $limit
 * @property string $filter
 */
class RssChannel extends \yii\db\ActiveRecord
{
    public $bannerGroups;
    public $articleCategories;
    public $is_published;
    public $is_turbopage;
    public $send_zen;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rss_channels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'container_template', 'item_template'], 'required'],
            [['container_template', 'item_template', 'filter', 'image_template'], 'string'],
            [['limit', 'is_turbopage', 'is_published', 'send_zen'], 'integer'],
            [['title'], 'string', 'max' => 250],
            [['alias'], 'string', 'max' => 50],
            [['alias'], 'unique'],
            ['bannerGroups','checkIsArray'],
            ['articleCategories','checkIsArray'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'alias' => 'Алиас',
            'container_template' => 'Шаблон главного контейнера',
            'item_template' => 'Шаблон элемента',
            'image_template' => 'Шаблон изображения',
            'limit' => 'Максимальное кол-во элементов в файле',
            'filter' => 'Фильтры',
            'articleCategories' => 'Категории статей',
            'bannerGroups' => 'Баннерные группы',
        ];
    }

    public function checkIsArray($attribute){
        if(!is_array($this->$attribute)){
            $this->addError($attribute, $attribute.' is not array!');
        }
    }

    public function unserializeFilter()
    {
        $filter = unserialize($this->filter);
        return $this->load($filter, '');   
    }

    public function generateRss(int $page = null, int $lastDays = 1)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $articles = $this->getArticles($page, $lastDays);

        if(count($articles) == 0) {
            return false;
        }

        foreach ($articles as $key => $article) {
            $item = $this->item_template;
            preg_match_all('/{{(.+?)}}/', $item, $params);
            foreach ($params[1] as $key => $param) {
                if(!in_array($param, ['images', 'image_preview', 'published_at_rfc', 'updated_at_rfc', 'warning', 'description', 'category'])) {
                    $item = str_replace($params[0][$key], $article->$param, $item);
                }
            }

            $item = $this->insertImages($item);
            $item = str_replace('{{image_preview}}', $article->getThumb(300, 200), $item);
            $item = str_replace('{{published_at_rfc}}', date(DATE_RFC822, $article->published_at), $item);
            $item = str_replace('{{updated_at_rfc}}', gmdate("d.m.Y", $article->updated_at), $item);
            $item = str_replace('{{category}}', $article->category->title, $item);
            $item = str_replace('{{description}}', $article->articleMeta->meta_description, $item);

            $medicalCategories = ArticleCategory::find()
                ->where(['is_medical' => true])
                ->select('id')
                ->column();
            $item = str_replace('{{warning}}', ((in_array($article->category_id, $medicalCategories) ? '<p>Внимание! Информация представленная в статье носит ознакомительный характер. Материалы статьи не призывают к самостоятельному лечению. Только квалифицированный врач может поставить диагноз и дать рекомендации по лечению исходя из индивидуальных особенностей конкретного пациента.</p>' : '')), $item);

            $rssItems[] = $item;
        }

        $configs = Yii::$app->params['metas'];

        $rssContent = str_replace([
            '{{container}}',  
            '{{site_description}}'
        ], [
            implode('', $rssItems),
            $configs['description']
        ], $this->container_template);

        return $rssContent;
    }

    /**
     * Вставляет в элемент список изображений
     * @param $item
     * @return string
     * @throws \yii\base\Exception
     */
    private function insertImages(string $item)
    {
        if(!$this->image_template) {
            return $item;
        }

        preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item, $images);
        foreach ($images[1] as $key => $value) {
            $images[1][$key] = str_replace('{{src}}', $value, $this->image_template);
        }

        return str_replace('{{images}}', implode('', $images[1]), $item);
    }

    /**
     * Получает статьи по фильтру канала и странице
     * @param $page|null
     * @return array
     * @throws \yii\base\Exception
     */
    private function getArticles(int $page = null, int $lastDays = 1)
    {
        $this->unserializeFilter();

        $rssItems = [];
        $articles = Article::find();
        if($this->is_turbopage) {
            $articles->andWhere(['is_turbopage' => ($this->is_turbopage == 1) ? 1 : 0]);
        }
        if($this->send_zen) {
            $articles->andWhere(['send_zen' => ($this->send_zen == 1) ? 1 : 0]);
        }
        if($this->is_published) {
            $articles->published();
        }
        if($this->bannerGroups) {
            $articles->andWhere(['in', 'banner_group_id', $this->bannerGroups]);
        }
        if($this->articleCategories) {
            $articles->andWhere(['in', 'category_id', $this->articleCategories]);
        }
        
        if(is_integer($page)) {
            $articles->offset($this->limit * $page);
        } else {
            $articles->andWhere(['>=', 'updated_at', time() - 60 * 60 * $lastDays * 24]);
        }

        $articles = $articles
            ->limit($this->limit)
            ->orderBy('id DESC')
            ->all();

        return $articles;
    }
}
