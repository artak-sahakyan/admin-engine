<?php

namespace common\models;

use Yii;
use Core\Domain\Exception;
use yii\behaviors\TimestampBehavior;
use yii\httpclient\Client;

/**
 * This is the model class for table "article_youtube".
 *
 * @property int $id
 * @property int $article_id
 * @property int $missed_position
 * @property string $title
 * @property string $cover
 * @property string $link
 * @property int $created_at
 * @property int $updated_at
 * @property int $missed_updated_at
 */
class ArticleYoutube extends \yii\db\ActiveRecord
{
    const PREG_YOUTUBE_URL = '/\youtube.com\/embed\/(.+?)\"/';
    const PREG_YOUTUBE = '/\[youtube id=\&quot;(.+?)\&quot;/';
    const PREG_YOUTUBE_SHORTCODE = '/\[youtube id=\"(.+?)\"/';
    const PREG_LAZY_YOUTUBE = '/\[lazyyoutube id=\"(.+?)\"\]/';

    const OEMBED_URL = 'https://www.youtube.com/oembed?url=';
    const EMBED_URL = 'https://www.youtube.com/watch?v=';

     /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_youtube';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'link'], 'required'],
            [['article_id', 'missed_position', 'created_at', 'updated_at', 'missed_updated_at'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['title', 'cover', 'link'], 'string', 'max' => 256],
            [['title', 'cover'], 'default', 'value' => ''],
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
            'missed_position' => 'Позиция',
            'link' => 'Ссылка',
            'missed_updated_at' => 'Обновлено',
        ];
    }

    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    /**
     * Возвращает все найденные youtube ссылки в тексте
     *
     * @param string $url
     * @return array
     */
    public static function getUrls(string $content)
    {
        preg_match_all(self::PREG_YOUTUBE_URL, $content, $sources);
        preg_match_all(self::PREG_YOUTUBE, $content, $sources1);
        preg_match_all(self::PREG_YOUTUBE_SHORTCODE, $content, $sources2);
        preg_match_all(self::PREG_LAZY_YOUTUBE, $content, $sources3);

        $sources = array_merge($sources[1], $sources1[1], $sources2[1], $sources3[1]);

        return array_unique($sources);
    }

    /**
     * Проверяет, что видео доступно для просмотра
     * Видео может не существовать, либо может быть закрыт доступ к нему
     *
     * @param string $url
     * @return bool
     */
    public static function checkVideo(string $url)
    {
        $httpClient = new Client();
        $response = $httpClient->createRequest()
            ->setMethod('get')
            ->setUrl(self::oembedUrl($url))
            ->send();
            
        return $response->isOk;
    }

    /**
     * Fetch json data from https://www.youtube.com/oembed
     * @param string $link youtube video id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function fetchOembedData(string $link)
    {
        $url = static::OEMBED_URL . static::EMBED_URL . $link;

        $httpClient = new Client();
        $response = $httpClient->createRequest([
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON,
                ]
            ])
            ->setMethod('get')
            ->setUrl($url)
            ->send();

        return [
            'status' => $response->isOk,
            'data' => $response->isOk ? $response->data : [],
        ];
    }

    /**
     * Формирует ссылку на youtube для проверки
     *
     * @param string $url
     * @return string
     */
    public function oembedUrl(string $url)
    {
        return self::OEMBED_URL . self::embedUrl($url) . '&format=json';
    }

    /**
     * Формирует рабочую ссылку на youtube
     *
     * @param string $url
     * @return string
     */
    public static function embedUrl(string $url)
    {
        $urlArray = explode('?', $url);
        return self::EMBED_URL . $urlArray[0];
    }
}
