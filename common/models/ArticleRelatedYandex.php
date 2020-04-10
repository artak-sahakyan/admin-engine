<?php

namespace common\models;

use articles;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "articles_related_yandex".
 *
 * @property int $id
 * @property int $article_id
 * @property int $related_article_id
 * @property int $position
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Articles $article
 */
class ArticleRelatedYandex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%articles_related_yandex}}';
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'related_article_id'], 'required'],
            [['article_id', 'related_article_id', 'position', 'created_at', 'updated_at'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'related_article_id' => 'Related Article ID',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }
}
