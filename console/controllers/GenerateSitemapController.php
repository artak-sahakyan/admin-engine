<?php

namespace console\controllers;

use console\helpers\QueryHelper;
use console\helpers\Console;
use Yii;
use common\helpers\SitemapHelper;
use common\models\Article;
use common\models\ArticleCategory;
use yii\console\Controller;
use yii\console\ExitCode;

class GenerateSitemapController extends ConsoleController
{
    /**
     * Site domain.
     * Need for make correct links.
     * @var string
     */
    private $_domain = '';

    public function actionIndex()
    {
        ini_set('max_execution_time', 60 * 5);

        $this->_domain = Yii::$app->urlManager->baseUrl;

        $this->startCommand();

        $this->console->output('Fetch categories');

        $categoryRows = $this->_fetchCategories();

        $this->console->output( 'Found ' . sizeof($categoryRows) . ' categories' );
        $this->console->output('Make tree category urls');

        // make category tree urls
        $treeCategories = [];
        $this->_treeCategoriesUrl($categoryRows, $pid = 0, $path = '', $treeCategories);

        $this->console->output('Made ' . sizeof($treeCategories) . ' category urls');

        $this->_writeCategories($treeCategories);

        $this->console->output('Categories url wrote to xml');

        // Articles
        $sitemapParts = $this->_writeArticles();

        // sitemap parts
        $this->console->output('Sitemap map write to xml');

        $this->_writeSitemapParts($sitemapParts);

        $this->stopCommand();
        return ExitCode::OK;
    }

    /**
     * @return array
     */
    private function _fetchCategories()
    {
        $categories = ArticleCategory::find()->select('id, parent_id, slug, updated_at')->orderBy('id, parent_id')->all();
        $categoryRows = [];
        foreach ($categories as $category) {
            $id = $category->id;
            $parentId = $category->parent_id ?? 0;
            $slug = $category->slug;
            $updatedAt = $category->updated_at;

            $categoryRows[$id] = [
                'id' => $id,
                'parent_id' => $parentId,
                'slug' => $slug,
                'updated_at' => $updatedAt,
            ];
        }

        return $categoryRows;
    }

    /**
     * Create tree of category.
     * @param array $rows
     * @param int $pid
     * @param string $uri
     * @param array $treeCategories
     */
    private function _treeCategoriesUrl(array $rows, int $pid, string $uri, array &$treeCategories)
    {
        foreach ($rows as $row) {
            if ($row['parent_id'] == $pid) {
                $currentUri = $uri . "/" . $row['slug'];
                $treeCategories[$row['id']] = [
                    'uri' => $currentUri,
                    'updated_at' => $row['updated_at'],
                ];
                $this->_treeCategoriesUrl($rows, $row['id'], $currentUri, $treeCategories);
            }
        }
    }

    /**
     * Write header, content, footer in file.
     * @param array $treeCategories
     */
    private function _writeCategories(array $treeCategories)
    {
        $sitemapHelper = new SitemapHelper();

        // filepath sitemap_categories
        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/sitemaps';
        $fileName = 'sitemap_categories';
        $sitemapPath = $sitemapHelper->makePath($catalogPath, null, $fileName);
        $sitemapHelper->fileOpen($sitemapPath);
        $sitemapHelper->fileChmod($catalogPath);
        $sitemapHelper->fileChmod($sitemapPath);

        // template
        $templateHeader  = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $templateHeader .= '  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $templateFooter  = '</urlset>' . PHP_EOL;

        $templateItem  = '  <url>' . PHP_EOL;
        $templateItem .= '    <loc>{{loc}}</loc>' . PHP_EOL; // https://sovets.net/pregnancy
        $templateItem .= '    <lastmod>{{lastmod}}</lastmod>' . PHP_EOL; // 2019-01-01
        $templateItem .= '    <priority>{{priority}}</priority>' . PHP_EOL; // 0.4
        $templateItem .= '    <changefreq>{{changefreq}}</changefreq>' . PHP_EOL; // yearly
        $templateItem .= '  </url>' . PHP_EOL;

        // write header
        $sitemapHelper->fileWrite($templateHeader);

        // write data
        foreach ($treeCategories as $category) {
            $lastmod = $category['updated_at'];

            if (!is_null($lastmod)) {
                $lastmod = strftime('%Y-%m-%d', $lastmod);
            }

            $itemSet = [
                'loc' => $this->_domain . $category['uri'],
                'lastmod' => $lastmod,
                'priority' => 0.4,
                'changefreq' => 'yearly',
            ];

            $item = $sitemapHelper->makeItem($templateItem, $itemSet);
            $sitemapHelper->fileWrite($item);
        }
        $sitemapHelper->fileWrite($templateFooter);
        $sitemapHelper->fileClose();
    }

    /**
     * Write article(fetch and write). Return sitemap parts.
     * @return array
     */
    private function _writeArticles():array
    {
        $sitemapHelper = new SitemapHelper();

        // template
        $templateHeader  = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $templateHeader .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $templateFooter = '</urlset>' . PHP_EOL;

        $templateItem  = '  <url>' . PHP_EOL;
        $templateItem .= '    <loc>{{loc}}</loc>' . PHP_EOL; // https://sovets.net/other/hi-tech/ofisnaya-tekhnika
        $templateItem .= '    <lastmod>{{lastmod}}</lastmod>' . PHP_EOL; // 2019-01-01
        $templateItem .= '    <priority>{{priority}}</priority>' . PHP_EOL; // 0.5
        $templateItem .= '    <changefreq>{{changefreq}}</changefreq>' . PHP_EOL; // yearly
        $templateItem .= '  </url>' . PHP_EOL;

        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/sitemaps';
        $page = 0;
        $sitemapPath = $sitemapHelper->makePath($catalogPath, $page, 'sitemap');
        $sitemapHelper->fileOpen($sitemapPath);
        $sitemapHelper->fileChmod($sitemapPath);

        // write header
        $sitemapHelper->fileWrite($templateHeader);

        // last update article
        $articleLastChanged = Article::find()->orderBy('updated_at')->limit(1)->one();
        $lastmod = null;
        if (!is_null($articleLastChanged['updated_at'])) {
            $lastmod = strftime('%Y-%m-%d', $articleLastChanged['updated_at']);
        }
        $itemSet = [
            'loc' => $this->_domain,
            'lastmod' => $lastmod,
            'priority' => 1.0,
            'changefreq' => 'daily',
        ];
        $item = $sitemapHelper->makeItem($templateItem, $itemSet);
        $sitemapHelper->fileWrite($item);

        if ($articleLastChanged) {
            $this->console->output('Found last modified article');
        }


        // write articles

        $query = Article::find()->where(['is_published' => 1])->orderBy('id');
        $articlesSize = $query->count();

        $perPage = Yii::$app->params['sitemapPerPage'];
        $i = 1;
        $page = 0;
        $progressDone = 0;
        $sitemapParts = [];
        $prevLastmod = null;

        $this->console->output("Total articles $articlesSize");
        $this->console->output('Fetch and write articles to xml perpage = ' . $perPage);

        $this->console->startProgress($progressDone, $articlesSize);

        foreach (QueryHelper::getRows($query) as $article) {

            if (isset($lastmod)) {
                $prevLastmod = $lastmod;
            }

            if ($i % $perPage == 0) {

                $sitemapParts[$page] = $prevLastmod;
                $page++;

                $sitemapHelper->fileWrite($templateFooter);
                $sitemapHelper->fileClose();

                $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web/sitemaps';
                $sitemapPath = $sitemapHelper->makePath($catalogPath, $page, 'sitemap');
                $sitemapHelper->fileOpen($sitemapPath);
                $sitemapHelper->fileChmod($sitemapPath);
                $sitemapHelper->fileWrite($templateHeader);
            }

            $lastmod = null;
            if (!is_null($article['updated_at'])) {
                $lastmod = strftime('%Y-%m-%d', $article['updated_at']);
            }

            $item = [
                'loc' => $this->_domain . $article->getUrl(),
                'lastmod' => $lastmod,
                'priority' => 0.5,
                'changefreq' => 'monthly',
            ];
            $item = $sitemapHelper->makeItem($templateItem, $item);
            $sitemapHelper->fileWrite($item);
            $i++;
            $this->console->updateProgress(++$progressDone, $articlesSize);
        }
        $this->console->endProgress();
        $sitemapParts[$page] = $prevLastmod;

        $this->console->output('Wrote ' . $articlesSize . ' articles');

        $sitemapHelper->fileWrite($templateFooter);
        $sitemapHelper->fileClose();

        return $sitemapParts;
    }

    /**
     * Write sitemap parts.
     * @param array $sitemapParts
     */
    private function _writeSitemapParts(array $sitemapParts)
    {
        $sitemapHelper = new SitemapHelper();

        // template
        $templateHeader  = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $templateHeader .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $templateFooter = '</sitemapindex>' . PHP_EOL;

        $templateItem  = '  <sitemap>' . PHP_EOL;
        $templateItem .= '    <loc>{{loc}}</loc>' . PHP_EOL; // https://sovets.net/sitemaps/sitemap_0.xml
        $templateItem .= '    <lastmod>{{lastmod}}</lastmod>' . PHP_EOL; // 2019-01-01
        $templateItem .= '  </sitemap>' . PHP_EOL;

        $catalogPath = Yii::getAlias('@sitePath') . '/frontend/web';
        $sitemapPath = $sitemapHelper->makePath($catalogPath, null, 'sitemap');
        $sitemapHelper->fileOpen($sitemapPath);
        $sitemapHelper->fileChmod($sitemapPath);

        // write header
        $sitemapHelper->fileWrite($templateHeader);

        // write sitemaps body
        $catalogPath = $this->_domain . '/sitemaps';
        foreach ($sitemapParts as $page => $lastMod) {
            $sitemapsPath = $sitemapHelper->makePath($catalogPath, $page, 'sitemap');
            $itemSet = [
                'loc' => $sitemapsPath,
                'lastmod' => $lastMod,
            ];
            $item = $sitemapHelper->makeItem($templateItem, $itemSet);
            $sitemapHelper->fileWrite($item);
        }

        // write categories path
        $sitemapCategoriesPath = $sitemapHelper->makePath($catalogPath, null, 'sitemap_categories');

        $categoryLastmod = ArticleCategory::find()->select('updated_at')->orderBy('updated_at DESC')->limit(1)->asArray()->one();
        $categoryLastmod = $categoryLastmod['updated_at'];
        if (!is_null($categoryLastmod)) {
            $categoryLastmod = strftime('%Y-%m-%d', $categoryLastmod);
        }
        $itemSet = [
            'loc' => $sitemapCategoriesPath,
            'lastmod' => $categoryLastmod,
        ];
        $item = $sitemapHelper->makeItem($templateItem, $itemSet);
        $sitemapHelper->fileWrite($item);

        // write footer
        $sitemapHelper->fileWrite($templateFooter);

        $sitemapHelper->fileClose();
    }

    private function getArticles()
    {
        $perPage = 100;
        $page = 0;
        $i = 0;
        $limit = null;
        while ($articles = Article::find()->orderBy('id')->limit($perPage)->offset($perPage * $page)->all()) {
            foreach ($articles as $article) {

                if (isset($limit) && $i >= $limit) {
                    break 2;
                }

                yield $article;

                $i++;
            }

            $page++;
        }
    }
}