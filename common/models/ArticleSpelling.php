<?php

namespace common\models;

use Yii;
use Yandex\Speller\SpellerClient;


/**
 * This is the model class for table "tools_article_spelling".
 *
 * @property int $id
 * @property int $article_id
 * @property string $content
 * @property string $title
 *
 * @property Articles $article
 */
class ArticleSpelling extends \yii\db\ActiveRecord
{

   // const YANDEX_SPELLING_URL = 'https://speller.yandex.net/services/spellservice.json/';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tools_article_spelling}}';
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
            [['title'], 'string', 'max' => 255],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            ['updated_at', 'safe']
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
            'content' => 'Содержание',
            'title' => 'Заголовок',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    public static function checkArticleAndSave(Article $article)
    {
        static::deleteAll([
            'article_id' => $article->id,
        ]);

        try {
            $errors = static::checkArticle($article);
        } catch (\Exception $err) {
            return null;
        }

        if(count($errors) > 0)
        {
            $articleSpelling = new ArticleSpelling();

            try {
                $articleSpelling->setAttributes($errors);
                $articleSpelling->save();
            } catch (\Exception $e) {
                return null;
            }

            return $articleSpelling;

        }

        return null;
        
    }

    public static function checkArticle(Article $article)
    {
        $spellerClient = new SpellerClient();

        $texts = [
            $article->title,
            $article->content,
        ];

        $result = $spellerClient->checkTexts($texts, [
            'lang'      => 'ru', 
            'options'   => 524,
            'format'    => SpellerClient::CHECK_TEXT_FORMAT_HTML
        ]);

        $articleErrors = [];

        $title      = self::getWords($result[0]);
        $content    = self::getWords($result[1]);

        if (sizeof($title) || sizeof($content)) {
            $articleErrors = [
                'article_id'    => $article->id,
                'title'         => serialize($title),
                'content'       => serialize($content),
                'updated_at'    => time()
            ];
        }


        return $articleErrors;
    }


    private function getWords(array $data) {
        $result = [];
        foreach ($data as $key => $value) {
            $except = ArticleSpellingExcept::find()->where(['title' => $value['word']])->count();
            if($except == 0) {
                $result[] = $value['word'];
            }
        }
        return $result;
    }
}
