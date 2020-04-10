<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_anchors".
 *
 * @property int $id
 * @property int $article_id
 * @property string $title
 * @property int $wordstat_count
 *
 * @property Article $article
 */
class ArticleAnchor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_anchors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id', 'wordstat_count'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'title' => 'Анкор',
            'wordstat_count' => 'Частотность',
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
