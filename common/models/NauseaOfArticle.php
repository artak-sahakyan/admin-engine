<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%nausea_of_articles}}".
 *
 * @property int $id
 * @property int $article_id
 * @property double $chapters
 * @property double $h1
 * @property double $title
 * @property double $description
 * @property double $keywords
 * @property double $alt
 * @property double $text
 * @property double $baden_points
 * @property double $bigram
 * @property double $trigram
 * @property double $word_density
 *
 * @property Article $article
 */
class NauseaOfArticle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%nausea_of_articles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'integer'],
            [['chapters', 'h1', 'title', 'description', 'keywords', 'alt', 'text', 'baden_points', 'bigram', 'trigram', 'word_density','miratext_water', 'miratext_bigram', 'miratext_trigram'], 'number'],
            [['miratext_words'], 'string'],
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
            'article_id' => 'ID статьи',
            'chapters' => 'Содержание',
            'h1' => 'H1',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'alt' => 'Alt',
            'text' => 'Текст',
            'baden_points' => 'Баден',
            'bigram' => 'Биграммы',
            'trigram' => 'Триграммы',
            'word_density' => 'Однословник',
            'miratext_water' => 'Водянистость',
            'miratext_bigram' => 'Биграммы',
            'miratext_trigram' => 'Триграммы',
            'miratext_words' => 'Слова'
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
     * @return array
     */
    static function defaultValuesForSearch()
    {
        return [
            'title' => 1,
            'h1' => 1,
            'description' => 1,
            'keywords' => 1,
            'alt' => 1.5,
            'trigram' => 1.5,
            'chapters' => 2,
            'bigram' => 2,
            'text' => 5.5,
            'baden_points' => 80,
            'word_density' => 2.5,
            'unique' => 10,
            'miratext_water' => 1
        ];
    }

    public function colored($attribute, $value, $newLimit = null) {
        $limit = ($newLimit) ? $newLimit : self::defaultValuesForSearch()[$attribute];

        return ($limit < $value) ? '<span class="red-cell">' . $value . '</span>' : $value;
    }

    public function setMiratextData(array $data)
    {
        $this->miratext_words = $data['miratext_words'];
        $this->miratext_bigram = $data['miratext_bigram'];
        $this->miratext_trigram = $data['miratext_trigram'];
        $this->miratext_water = $data['miratext_water'];
    }
}
