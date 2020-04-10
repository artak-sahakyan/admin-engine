<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_error_info".
 *
 * @property int $id
 * @property int $article_id
 * @property string $error_in_text
 * @property int $date_send
 *
 * @property Article $article
 */
class ArticleErrorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_error_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id'], 'integer'],
            [['error_in_text'], 'string'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            ['date_send', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Id Статьи',
            'error_in_text' => 'Ошибка в тексте',
            'date_send' => 'Дата отправки',
        ];
    }

    public function setTimeFormat($attributes)
    {

        foreach ($attributes as $attribute) {
            if(!empty($this->{$attribute})) {
                $this->{$attribute} = strtotime($this->{$attribute});
            }
        }

        return $this;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }
}
