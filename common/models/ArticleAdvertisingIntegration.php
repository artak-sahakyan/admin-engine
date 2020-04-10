<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_advertising_integrations".
 *
 * @property int $id
 * @property int $article_id
 * @property string $text
 * @property string $name
 * @property int $end_date
 * @property int $is_active
 * @property string $shortcode
 *
 * @property Article $article
 */
class ArticleAdvertisingIntegration extends \yii\db\ActiveRecord
{
    public $shortcode;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_advertising_integrations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'is_active'], 'integer'],
            [['article_id'], 'required'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'trim'],
            [['name'], 'default', 'value' => NULL],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['shortcode', 'end_date'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Статья (id)',
            'text' => 'Текст интеграции',
            'name' => 'Контакты',
            'end_date' => 'Дата завершения',
            'is_active' => 'Активность',
            'shortcode' => 'Шорткод',
        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }


    /**
     * before save convert to timestamp
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function setTimeFormat($attributes)
    {

        foreach ($attributes as $attribute) {
            if(!empty($this->{$attribute})) {
                $this->{$attribute} = strtotime($this->{$attribute});
            }
        }

        return $this;
    }

}
