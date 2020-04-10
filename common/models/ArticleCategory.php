<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article_categories}}".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $slug
 * @property string $title
 * @property string $h1Title
 * @property string $metaTitle
 * @property string $metaDescription
 * @property string $metaKeywords
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 * @property int sort
 * @property string $head_text
 *
 * @property Article[] $articles
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_categories}}';
    }

    public static function getRootCategoriesList()
    {
        return static::find()
            ->where(['IS', 'parent_id', null])
            ->select(['title', 'id'])
            ->indexBy('id')
            ->column();
    }

    public static function getChildsCategoriesList($parentId = null)
    {
        $query =  static::find()
            ->where(['IS NOT', 'parent_id', null])
            ->select(['title', 'id'])
            ->indexBy('id');

        if($parentId) {
            $query->andWhere(['parent_id' => $parentId]);
        }
        return $query->column();
    }

    public static function getAllCategoriesList() {
        $categories = static::find()->with('childs')->all();
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'title' => $category->title
            ];
            foreach ($category->childs as $child) {
                $result[] = [
                    'id' => $child->id,
                    'title' => $child->title
                ];
            }
        }
        return $result;
    }



    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($insert && !$this->sort) {
            static::updateAll(['sort' => $this->id], ['id' => $this->id]);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'created_at', 'updated_at', 'sort', 'is_medical'], 'integer'],
            [['title'], 'required'],
            [['text'], 'string'],
            [['slug', 'title', 'h1Title', 'metaTitle', 'metaDescription', 'metaKeywords'], 'string', 'max' => 255],
            ['head_text', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительская категория',
            'slug' => 'URL',
            'title' => 'Заголовок',
            'h1Title' => 'Заголовок (H1)',
            'metaTitle' => 'Meta Title',
            'metaDescription' => 'Meta Description',
            'metaKeywords' => 'Meta Keywords',
            'text' => 'Текст',
            'is_medical' => 'Медицинская',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'head_text' => 'Текст в хедере (head)',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['category_id' => 'id']);
    }

    public function getLastArticles()
    {
        return $this->getArticles()->select(['id', 'title', 'slug', 'category_id', 'description', 'image_color', 'image_extension', 'published_at', 'content', 'main_query', 'is_actual'])->orderBy('id DESC');
    }

    public function getChilds()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getChild()
    {
        return $this->hasOne(self::class, ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getParents()
    {
        return $this->hasMany(self::class, ['id' => 'parent_id']);
    }
}
