<?php


namespace console\controllers;


use common\models\Article;
use console\helpers\QueryHelper;
use Yii;
use yii\console\Controller;
use yii\imagine\Image;


class GlobalImportArticleImageController extends Controller
{
    public $readOnly = true;
    public $recompressExists = false;

    public $pathImages;
    public $quality = 80;

    private $baseUrl;

    public function init()
    {
        $this->pathImages = Yii::getAlias('@sitePath') . '/frontend/web/photos';

        $this->baseUrl = Yii::$app->urlManager->baseUrl;

        parent::init();
    }

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'readOnly',
            'recompressExists',
        ]);

        if ($this->readOnly) {
            echo "Script running readonly mode\n";
        }
    }

    /**
     * Article replace img to compressed
     */
    public function actionIndex()
    {
        foreach ($this->getArticles() as $article) {

            echo "Parse article {$article['id']} \n";
            $content = $article['content'];

            $imgFound = false;

            // photos/uploads
            $pattern = '~(?:"|\')(' . $this->baseUrl . '/photos/(uploads/\d+)/([^/]+)(\..+?))(?:"|\')~iu';
            if (preg_match_all($pattern, $content, $matches) > 0 ) {
                foreach ($matches[1] as $key => $url) {
                    $dir = $matches[2][$key];
                    $fileName = $matches[3][$key];
                    $fileExt = $matches[4][$key];
                    $uri = $dir . '/' . $fileName . $fileExt;
                    if (!file_exists($this->pathImages . '/' . $uri)) {
                        echo "Article " . $article['id'] . " not found picture in " . $this->pathImages . '/' . $uri . " \n";
                        continue;
                    }

                    // compress
                    if ($this->readOnly == false) {
                        $pathCompress = $this->pathImages . '/' . $dir . '/compress';
                        if (!file_exists($pathCompress)) {
                            mkdir($pathCompress, 0777, true);
                        }

                        if ($this->recompressExists == false && !file_exists($pathCompress . '/' . $fileName . '.jpg') ||
                            $this->recompressExists == true) {

                            $imagine = Image::getImagine();
                            $imagine->open($this->pathImages . '/' . $uri)
                                ->interlace(\Imagine\Image\ImageInterface::INTERLACE_PLANE)
                                ->save($pathCompress . '/' . $fileName . '.jpg', ['quality' => $this->quality]);
                        }
                    }

                    // replace url
                    $pattern = $matches[0][$key];
                    $baseUrl = $this->baseUrl;
                    $photoUrl = $baseUrl . '/photos';
                    $replacement = $photoUrl . '/' . $dir . '/compress/' . $fileName . '.jpg';
                    echo "[1] replace from $pattern to $replacement \n";
                    $pattern = preg_quote($pattern, '~');
                    $content = preg_replace($pattern, $replacement, $content);
                }

                $imgFound = true;
            }

            // uploads/posts
            $pattern = '~(?:"|\')(' . $this->baseUrl . '/uploads/(posts)/(.+?)(\..+?))(?:"|\')~iu';
            if (preg_match_all($pattern, $content, $matches) > 0 ) {
                foreach ($matches[1] as $key => $url) {
                    $dir = $matches[2][$key];
                    $fileName = $matches[3][$key];
                    if (preg_match('~/~', $fileName)) {
                        preg_match('~(.+)/(.+)~', $fileName, $matchess);
                        $subDir = $matchess[1];
                        $fileName = $matchess[2];

                        $dir .= '/' . $subDir;
                    }
                    $fileExt = $matches[4][$key];
                    $uri = $dir . '/' . $fileName . $fileExt;
                    if (!file_exists($this->pathImages . '/' . $uri)) {
                        echo "Article " . $article['id'] . " not found picture in " . $this->pathImages . '/' . $uri . " \n";
                        continue;
                    }

                    // compress
                    if ($this->readOnly == false) {
                        $pathCompress = $this->pathImages . '/' . $dir . '/compress';
                        if (!file_exists($pathCompress)) {
                            mkdir($pathCompress, 0777, true);
                        }

                        if ($this->recompressExists == false && !file_exists($pathCompress . '/' . $fileName . '.jpg') ||
                            $this->recompressExists == true) {

                            $imagine = Image::getImagine();
                            $imagine->open($this->pathImages . '/' . $uri)
                                ->interlace(\Imagine\Image\ImageInterface::INTERLACE_PLANE)
                                ->save($pathCompress . '/' . $fileName . '.jpg', ['quality' => $this->quality]);
                        }
                    }

                    // replace url
                    $pattern = $matches[0][$key];
                    $baseUrl = $this->baseUrl;
                    $photoUrl = $baseUrl . '/photos';
                    $replacement = $photoUrl . '/' . $dir . '/compress/' . $fileName . '.jpg';
                    echo "[2] replace from $pattern to $replacement \n";
                    $pattern = preg_quote($pattern, '~');
                    $content = preg_replace($pattern, $replacement, $content);
                }

                $imgFound = true;
            }

            if ($content != null && $imgFound == true && $this->readOnly == false) {
                Yii::$app->db->createCommand("
                    UPDATE `articles`
                    SET `content` = " . Yii::$app->db->quoteValue($content) . "
                    WHERE `id` = " . (int)$article['id'] . "
                ")->execute();

                echo "Updated article " . $article['id'] . "\n";
            }
        }
    }

    private function getArticles()
    {
        $query = Article::find()->orderBy('id');
        $query->asArray();

        $count = $query->count();
        echo "Total articles $count \n";

        foreach (QueryHelper::getRows($query) as $article) {
            yield $article;
        }
    }

    /**
     * Articles set current domain name for images
     *
     * @param $proto http protocol http or https
     * @param $domain
     * @throws \yii\db\Exception
     */
    public function actionDomainFix($proto, $domain)
    {
        foreach ($this->getArticles() as $article) {

            $content = $article['content'];
            $imgFound = false;

            echo "Replace article {$article['id']}\n";

            $pattern = '~' . $proto . $domain . '/(photos/uploads/\d+/compress)~iu';
            $pattern = preg_quote($pattern, '~');
            preg_match_all($pattern, $content, $matches);
            foreach ($matches[0] as $key => $match) {
                echo "Replace $match to $this->baseUrl/{$matches[1][$key]}\n";
                $imgFound = true;
            }

            $pattern = '~' . $proto . $domain . '/(photos/uploads/posts/compress)~iu';
            $pattern = preg_quote($pattern, '~');
            preg_match_all($pattern, $content, $matches);
            foreach ($matches[0] as $key => $match) {
                echo "Replace $match to $this->baseUrl/{$matches[1][$key]}\n";
                $imgFound = true;
            }

            if ($this->readOnly == false) {
                $pattern = '~' . $proto . $domain . '/(photos/uploads/\d+/compress)~iu';
                $pattern = preg_quote($pattern, '~');
                $content = preg_replace($pattern, $this->baseUrl . '/$1', $content);

                $pattern = '~' . $proto . $domain . '/(photos/uploads/posts/compress)~iu';
                $pattern = preg_quote($pattern, '~');
                $content = preg_replace($pattern, $this->baseUrl . '/$1', $content);
            }

            if ($content != null && $imgFound == true && $this->readOnly == false) {
                Yii::$app->db->createCommand("
                    UPDATE `articles`
                    SET `content` = " . Yii::$app->db->quoteValue($content) . "
                    WHERE `id` = " . (int)$article['id'] . "
                ")->execute();

                echo "Updated article " . $article['id'] . "\n";
            }

        }
    }
}