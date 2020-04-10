<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_rating".
 *
 * @property int $id
 * @property int $article_id
 * @property int $positive
 * @property int $negative
 *
 * @property Article $article
 */
class ArticleRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id', 'positive', 'negative'], 'integer'],
            [['article_id'], 'unique'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Статья',
            'positive' => 'Положительных оценок',
            'negative' => 'Отрицательных оценок',
            'comment' => 'Комментарии',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public function addPositive()
    {
        $this->positive += 1;
        $this->save();
        return $this->positive;
    }

    public function addNegative(string $comment)
    {
        $this->negative += 1;
        if(!empty($comment)) {
            $this->comment = $this->comment . $comment . ';';
        }
        $this->save();
        return $this->negative;
    }

    public function getComments() {
        return array_filter(explode(';', $this->comment));
    }
}
