<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_metas".
 *
 * @property int $id
 * @property int $article_id
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $words
 * @property string $main_word
 * @property int $unused_metrika_words_count
 * @property int $metrika_words_count
 * @property int $unique_users_yesterday_count
 * @property int $unique_users_all_time_count
 * @property int $updated_at
 *
 *
 */
class ArticleMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_metas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'unused_metrika_words_count', 'metrika_words_count', 'unique_users_yesterday_count', 'unique_users_all_time_count', 'updated_at'], 'integer'],
            [['meta_description'], 'string'],
            [['meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['words', 'main_word'], 'safe'],

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
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'meta_title' => 'Meta Title',
        ];
    }
}
