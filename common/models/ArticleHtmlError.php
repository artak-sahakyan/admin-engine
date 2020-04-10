<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_html_errors".
 *
 * @property int $id
 * @property int $article_id
 * @property string $content
 *
 * @property Articles $article
 */
class ArticleHtmlError extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_html_errors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id'], 'integer'],
            [['content'], 'string'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Статья',
            'content' => 'Ошибки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public function getErrorArray()
    {
        return array_filter(explode(';', $this->content));
    }
}
