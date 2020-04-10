<?php
namespace common\helpers;

use Yii;

class RssHelper
{
    /**
     * Генерирует файлы для RSS канала
     * @param \common\models\RssChannel $rssChannel
     * @return int
     */
	public static function generate(\common\models\RssChannel $rssChannel, int $lastDays = 2)
    {
        $res = true;
        $pages = 0;
        $alias = $rssChannel->alias;

        static::removeFiles($alias);
        
        $lastUpdated = $rssChannel->generateRss(null, $lastDays);
        if($lastUpdated) {
            static::saveRssToFile($alias, $lastUpdated);
        }

        while ($res) {
            if($res = $rssChannel->generateRss($pages)) {
                static::saveRssToFile($alias, $res, $pages++);
            }
        }
        
        return $pages;
    }

    /**
     * Сохраняет RSS файл
     * @param string $alias
     * @param string $rssContent
     * @param int|null $page
     * @return $this
     */
    public static function saveRssToFile(string $alias, string $rssContent, int $page = null)
    {
        $response = new \yii\web\Response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'text/xml');

        $catalogPath = static::getCatalogPath();
        $fileName = $alias . ((is_integer($page)) ? ('_' . $page) : '') . '.xml';
        $rssPath = $catalogPath . '/' . $fileName;

        $fp = fopen($rssPath, 'w');
        fwrite($fp, $rssContent);
        fclose($fp);
        $c = chmod($rssPath, 0666);
    }

    /**
     * Удаляет старые файлы rss, относящиеся к каналу
     * @param string $alias
     * @return $this
     */
    public static function removeFiles(string $alias)
    {
        foreach (static::getAllFiles($alias) as $file) {
           unlink($file);
        }
    }

    /**
     * Ищет все файлы rss, относящиеся к каналу
     * @param string $alias
     * @return array
     */
    public static function getAllFiles(string $alias)
    {
        return glob(static::getCatalogPath() . "/" . $alias . "*.xml");
    }

    /**
     * Отдает путь к rss
     * @return string
     */
    private static function getCatalogPath()
    {
        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/rss';
        if(!file_exists($catalogPath)) {
            mkdir(($catalogPath), 0777, true);
        }

        return $catalogPath;
    }

}
