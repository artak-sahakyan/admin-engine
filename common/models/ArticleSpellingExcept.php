<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tools_article_spelling_except".
 *
 * @property int $id
 * @property string $title
 * @property boolean $checked
 */
class ArticleSpellingExcept extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tools_article_spelling_except';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
            [['checked'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Слово',
            'checked' => 'Проверено',
        ];
    }

    public static function getAddedWords() {

        $words = static::find()
            ->select('title, id')
            ->indexBy('id')
            ->where([
                'checked' => 0
            ])
            ->column();

        $countArticles = 0;

        if($words) {
            $words = array_map('mb_strtolower', $words);
            $countArticles = Article::find()
                ->select(['id', 'title', 'content'])
                ->where(['OR LIKE',  'LOWER(content)', $words])
                ->orWhere(['OR LIKE', 'LOWER(title)', $words])
                ->count();
        }

        return [
          'wordCount' => count($words),
          'total' => $countArticles,
          'words' => $words
        ];
    }

}
