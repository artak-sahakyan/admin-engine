<?php
namespace console\controllers;

use Yii;
use yii\console\ExitCode;
use common\models\{ ArticleCategory, Article };
use common\helpers\FilesHelper;

class WpExportController extends ConsoleController
{
    public $categories = 0;

    public function options($actionID)
    {
        return ['categories'];
    }
    
    public function optionAliases()
    {
        return ['categories' => 'categories'];
    }

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '2024M');
        ini_set('max_execution_time', 0);

        

        $this->startCommand();

        $countFiles = self::processCategories();

        $countArticles = Article::find()->count();
        for($i = 0; $i <= $countArticles/100; $i++) {
            $countFiles += self::processArticles($i);
        }
        
        $this->console->output('Файлы для экспорта созданы (' . $countFiles . ' файлов)');

        $this->stopCommand();
        return ExitCode::OK;
    }

    private function processCategories()
    {
        $title = Yii::$app->params['metas']['title'];
        $description = Yii::$app->params['metas']['description'];
        $baseUrl = 'https://' . Yii::$app->params['currentSiteHost'];

        $categories = ArticleCategory::find()->all();
        $countFiles = 0;
        $result = '<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
    <title>' . $title . '</title>
    <link>' . $baseUrl . '</link>
    <description>' . $description . '</description>
    <pubDate>' . date('r') . '</pubDate>
    <language>ru-RU</language>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>' . $baseUrl . '</wp:base_site_url>
    <wp:base_blog_url>' . $baseUrl . '</wp:base_blog_url>

        <wp:author><wp:author_id>1</wp:author_id><wp:author_login><![CDATA[admin]]></wp:author_login><wp:author_email><![CDATA[admin@allslim.ru]]></wp:author_email><wp:author_display_name><![CDATA[admin]]></wp:author_display_name><wp:author_first_name><![CDATA[]]></wp:author_first_name><wp:author_last_name><![CDATA[]]></wp:author_last_name></wp:author>';

        $this->console->output('Всего ' . count($categories) . ' категорий');

        foreach ($categories as $key => $category) {
            $result .= '<wp:category>
                <wp:term_id>' . $category->id . '</wp:term_id>
                <wp:category_nicename><![CDATA[' . $category->slug . ']]></wp:category_nicename>
                <wp:category_parent><![CDATA[' . $category->parent_id . ']]></wp:category_parent>
                <wp:cat_name><![CDATA[' . $category->title . ']]></wp:cat_name>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[h1]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->h1Title . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_h1]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5d6fa72beeba4]]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[description]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->metaDescription . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_description]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5bd82c7263f8c]]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[keywords]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->metaKeywords . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_keywords]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5bd82c8363f8d]]></wp:meta_value>
                </wp:termmeta>
            </wp:category>';
        }

        foreach ($categories as $key => $category) {
            $result .= '<wp:term>
                <wp:term_id><![CDATA[' . $category->id . ']]></wp:term_id>
                <wp:term_taxonomy><![CDATA[category]]></wp:term_taxonomy>
                <wp:term_slug><![CDATA[' . $category->slug . ']]></wp:term_slug>
                <wp:term_parent><![CDATA[' . (($category->parent_id) ? $category->parent->slug : '') . ']]></wp:term_parent>
                <wp:term_name><![CDATA[' . $category->title . ']]></wp:term_name>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[h1]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->h1Title . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_h1]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5d6fa72beeba4]]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[description]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->metaDescription . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_description]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5bd82c7263f8c]]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[keywords]]></wp:meta_key>
                    <wp:meta_value><![CDATA[' . $category->metaKeywords . ']]></wp:meta_value>
                </wp:termmeta>
                <wp:termmeta>
                    <wp:meta_key><![CDATA[_keywords]]></wp:meta_key>
                    <wp:meta_value><![CDATA[field_5bd82c8363f8d]]></wp:meta_value>
                </wp:termmeta>
            </wp:term>';
        }

        $result .= '<generator>https://wordpress.org/?v=5.2.2</generator></channel></rss>';

        $response = new \yii\web\Response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'text/xml');

        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/wordpress';
        if(!file_exists($catalogPath)) {
            mkdir(($catalogPath), 0777, true);
        }

        $rssPath = $catalogPath . '/categories.xml';

        $fp = fopen($rssPath, 'w');
        fwrite($fp, $result);
        fclose($fp);
        $c = chmod($rssPath, 0666);

        return 1;
    }

    private function processArticles($fileNum)
    {
        $title = Yii::$app->params['metas']['title'];
        $description = Yii::$app->params['metas']['description'];
        $baseUrl = 'https://' . Yii::$app->params['currentSiteHost'];

        $articles = Article::find()->offset($fileNum*100)->limit(100)->all();
        $countFiles = 0;
        $result = '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
        <channel><title>' . $title . '</title><link>' . $baseUrl . '</link><description>' . $description . '</description><pubDate>' . date('r') . '</pubDate><language>ru-RU</language><wp:wxr_version>1.2</wp:wxr_version><wp:base_site_url>' . $baseUrl . '</wp:base_site_url><wp:base_blog_url>' . $baseUrl . '</wp:base_blog_url><generator>https://wordpress.org/?v=5.2.3</generator>';

        $this->console->output('Всего ' . count($articles) . ' статей');

        foreach ($articles as $key => $article) {
            $result .= '<item>
            <title>' . $article->articleMeta->meta_title . '</title>
            <link>' . $baseUrl . '/' . $article->url . '</link>
            <pubDate>' . date('r', $article->published_at) . '</pubDate>
            <dc:creator><![CDATA[admin]]></dc:creator>
            <guid isPermaLink="false">' . $baseUrl . '/?p=' . $article->id . '</guid>
            <description><![CDATA[' . $article->articleMeta->meta_description . ']]></description>
            <content:encoded><![CDATA[<img src="' . $baseUrl . $article->getThumb(500, 400, 'aspectRatio') . '"><h1>' . $article->title . '</h1>' . $article->contentProcessors([
                'navigation' => false,
                'markup'    => false,
                'votings'   => false,
                'videos'    => false,
                'related'   => false,
                'turbo'     => false,
                'amp'       => false,
                'banners'   => false
            ])->displayContent . ']]></content:encoded>
            <excerpt:encoded><![CDATA[]]></excerpt:encoded>
            <wp:post_id>' . $article->id . '</wp:post_id>
            <wp:post_date><![CDATA[' . date('Y-m-d H:i:s', $article->published_at) . ']]></wp:post_date>
            <wp:post_date_gmt><![CDATA[' . date('Y-m-d H:i:s', $article->published_at) . ']]></wp:post_date_gmt>
            <wp:comment_status><![CDATA[open]]></wp:comment_status>
            <wp:ping_status><![CDATA[open]]></wp:ping_status>
            <wp:post_name><![CDATA[' . $article->slug . ']]></wp:post_name>
            <wp:status><![CDATA[publish]]></wp:status>
            <wp:post_parent>0</wp:post_parent>
            <wp:menu_order>0</wp:menu_order>
            <wp:post_type><![CDATA[post]]></wp:post_type>
            <wp:post_password><![CDATA[]]></wp:post_password>
            <wp:is_sticky>0</wp:is_sticky>
            <category domain="category" nicename="' . (($article->category) ? $article->category->slug : '') . '">
                <![CDATA[' . (($article->category) ? $article->category->h1Title : '') . ']]>
            </category>
            <wp:postmeta>
                <wp:meta_key><![CDATA[description]]></wp:meta_key>
                <wp:meta_value><![CDATA[' . $article->articleMeta->meta_description . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key><![CDATA[_description]]></wp:meta_key>
                <wp:meta_value><![CDATA[field_5d6fab13a36fe]]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key><![CDATA[keywords]]></wp:meta_key>
                <wp:meta_value><![CDATA[' . $article->articleMeta->meta_keywords . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key><![CDATA[_keywords]]></wp:meta_key>
                <wp:meta_value><![CDATA[field_5d6fab13a370f]]></wp:meta_value>
            </wp:postmeta>
        </item>';
        }

        $result .= '</channel></rss>';

        $response = new \yii\web\Response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'text/xml');

        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/wordpress';
        if(!file_exists($catalogPath)) {
            mkdir(($catalogPath), 0777, true);
        }

        $rssPath = $catalogPath . '/articles_' . $fileNum . '.xml';

        $fp = fopen($rssPath, 'w');
        fwrite($fp, $result);
        fclose($fp);
        $c = chmod($rssPath, 0666);

        return 1;
    }
}
