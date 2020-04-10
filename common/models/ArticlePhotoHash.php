<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_photo_hashes".
 *
 * @property int $id
 * @property int $article_id
 * @property int $type
 * @property string $path
 * @property string $hash
 *
 * @property Article $article
 */
class ArticlePhotoHash extends \yii\db\ActiveRecord
{
    const PREVIEW = 1;
    const CONTENT = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_photo_hashes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'type', 'path', 'hash'], 'required'],
            [['article_id', 'type'], 'integer'],
            [['path', 'hash'], 'string', 'max' => 255],
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
            'type' => 'Тип',
            'path' => 'Путь к файлу',
            'hash' => 'HASH',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public static function calcHashAndSave(Article $article, int $type)
    {
        if($type = ArticlePhotoHash::PREVIEW)
            self::calcPreviewHashAndSave($article);
        else
            self::calcContentHashAndSave($article);
    }

    private function calcPreviewHashAndSave(Article $article)
    {
        $image = $article->getThumb(50, 50);

        if(file_exists($image)) {
            ArticlePhotoHash::deleteAll([
                'AND',
                'type' => ArticlePhotoHash::PREVIEW,
                ['article_id' => $article->id]
            ]);

            $articlePhotoHash = new ArticlePhotoHash([
                'path'          => $image,
                'article_id'    => $article->id,
                'hash'          => sha1_file($image),
                'type'          => ArticlePhotoHash::PREVIEW
            ]);

            $articlePhotoHash->save();
        }
    }

    private function calcContentHashAndSave(Article $article)
    {
        $article_id = $article->id;
        $images = self::getImagesFromArticle($article->content);

        ArticlePhotoHash::deleteAll([
            'AND',
            'type' => ArticlePhotoHash::CONTENT,
            ['article_id' => $article->id]
        ]);

        foreach ($images as $image) {
            if(file_exists($image)) {
                $articlePhotoHash = new ArticlePhotoHash([
                    'path'          => $image,
                    'article_id'    => $article_id,
                    'hash'          => sha1_file($image),
                    'type'          => ArticlePhotoHash::CONTENT
                ]);

                $articlePhotoHash->save();
            }
        }
    }

    /**
     * Preg match images from content string
     * @param string $content
     * @return array
     */
    private function getImagesFromArticle($content)
    {
        preg_match_all('/<img.+(src="(.+?)")/', $content, $matches);

        return isset($matches[2]) ? $matches[2] : [];
    }

}
