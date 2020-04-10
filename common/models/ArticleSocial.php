<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_socials".
 *
 * @property int $id
 * @property int $article_id
 * @property int $sended_vk
 */
class ArticleSocial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_socials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id', 'sended_vk', 'sended_ok'], 'integer'],
            [['article_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Статья',
            'sended_vk' => 'Отправлена в Vk',
            'sended_ok' => 'Отправлена в Ok',
        ];
    }
}
