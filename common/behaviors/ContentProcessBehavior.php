<?php
namespace common\behaviors;

use common\helpers\ArticleRemoveDiv;
use common\widgets\{ ShortcodeWidget, BannerWidget, ContentNavigationWidget };
use yii\base\Behavior;

class ContentProcessBehavior extends Behavior
{
    const PREG_SHORTCODE    = '/\[(.+?)\]/';
    const PREG_BANNER       = '/\[banner(.+?)\]/';
    const PREG_RELATED      = '/\[banner(.+?)alias="article_related(.+?)\]/';
    const PREG_TURBO        = '/\[turbo(.+?)\]/';
    const PREG_SOURCE       = '#\\[sources\\](.+?)\\[/sources\\]#is';
    const PREG_YOUTUBE      = '#\\[videos title="right"\\](.+?)\\[/videos\\]#is';
    const PREG_VOTING       = '/\\[voting(.+?)\]/';
    const PREG_SEOHIDE      = '/\\[seohide(.+?)\]/';
    const PREG_REVIEW       = '/\\[review(.+?)\]/';
    const PREG_ADVERT       = '/\[article_advertising_place (.+?)\]/';
    const PREG_ALIAS        = '#alias="([^"]+)"#i';
    const PREG_LAZYYOUTUBE  = '/\[lazyyoutube(.+?)\]/';

    private $content;
    private $article;

    private static $forWeb = true;
    private static $amp = false;

    public function contentProcessors(array $data = [])
    {
        $default = [
            'navigation' => true,
            'markup'    => true,
            'votings'   => true,
            'videos'    => true,
            'related'   => false,
            'turbo'     => false,
            'amp'       => false,
            'banners'   => true
        ];

        $data = array_merge($default, $data);

        $this->content = htmlspecialchars_decode($this->owner->content);
        $this->article = $this->owner;

        self::$forWeb = $data['videos'];
        self::$amp = $data['amp'];

        self::clearDivWithoutClass();

        if($data['markup']) {
            self::markup();
        }

        self::processingShortcodes(self::PREG_ADVERT, 'advertisingIntegration');

        if($data['votings']) {
            self::processingShortcodes(self::PREG_VOTING, 'votings');
        }

        if($data['videos']) {
            self::processingShortcodes(self::PREG_SOURCE, 'videos');
        }
        
        self::processingShortcodes(self::PREG_REVIEW, 'review');
        self::processingShortcodes(self::PREG_YOUTUBE, 'sources');
        self::processingShortcodes(self::PREG_LAZYYOUTUBE, 'lazyyoutube');
        self::processingShortcodes(self::PREG_SEOHIDE, 'seohide');

        if($data['navigation']) {
            self::setContentNavigation();
        }
        if($data['related']) {
            self::processingShortcodes(self::PREG_RELATED, 'related');
        }
        if($data['banners']) {
            self::processingBanners();
        }
        // if($data['turbo']) {
        //     self::addFirstTurboPlace();
        //     self::processingShortcodes(self::PREG_TURBO, 'turbo');
        // }
        // if($data['amp']) {
        //     self::processingShortcodes(self::PREG_TURBO, 'amp');
        //     self::prepareAmp();
        // }
        self::clearUnusedShortcodes();
        self::clearEmptyTags();

        $this->owner->displayContent = $this->content;

        return $this->owner;
    }

    protected function processingShortcodes(string $preg, string $template)
    {
        $content = $this->content;
        try {

            preg_match_all($preg, $content, $sources);

            if($template == 'shortcode') $sources[1] = $sources[0];

            foreach ($sources[1] as $key => $source) {

                $sourcesWidget = ShortcodeWidget::widget([
                    'template'  => $template,
                    'sources'   => $source,
                    'article'   => $this->article,
                    'forWeb'    => (self::$forWeb && !self::$amp),
                ]);

                $content = str_replace($sources[0][$key], $sourcesWidget, $content);
            }

            $this->content = $content;

        } catch (\Throwable $error) {
            \Yii::error($error);
            $this->content = $content;
        }
    
        return $this;
    }

    protected function processingBanners()
    {
        $content = $this->content;

        preg_match_all(self::PREG_BANNER, $content, $bannerPlaces);

        $places = [];
        foreach ($bannerPlaces[1] as $key => $value) {
            preg_match(self::PREG_ALIAS, $value, $alias);
            if(!empty($alias) && isset($alias[1])) {
                $places[] = $alias[1];
            }
        }

        $banners = GetBannersBehavior::getAllBanners($this->article->bannerGroup, $places);

        foreach ($bannerPlaces[1] as $key => $place) {

            preg_match(self::PREG_ALIAS, $place, $alias);

            foreach ($banners as $banner) {
                if(isset($alias[1]) && $banner->place->alias == $alias[1]) {

                    $bannerWidget = BannerWidget::widget([
                        'banner'    => $banner,
                        'alias'     => $alias[1],
                        'article'   => $this->article
                    ]);

                    $content = str_replace($bannerPlaces[0][$key], $bannerWidget, $content);
                    break;
                }                    
            }
        }

        $this->content = $content;

        return $this;
    }

    public function votings($showPlace = '')
    {
        static $votings;

        if (empty($votings)) {
            $votings = GetVotingsBehavior::getAllVotings($this->article->bannerGroup, $this->article->category, $this->article);
        }

        $votingsPlace = [];
        foreach ($votings as $voting) {
            $place = '';
            if (!empty($voting['show_sidebar'])) {
                $place = 'show_sidebar';
                $votingsPlace[$place] = &$voting;
            }
            if (!empty($voting['show_bottom'])) {
                $place = 'show_bottom';
                $votingsPlace[$place] = &$voting;
            }
            if (!empty($voting['show_main'])) {
                $place = 'show_main';
                $votingsPlace[$place] = &$voting;
            }
            if (!empty($voting['show_article'])) {
                $place = 'show_article';
                $votingsPlace[$place] = &$voting;
            }
        }

        if (empty($showPlace)) {
            return $votingsPlace;
        }

        return $votingsPlace[$showPlace] ?? '';

    }

    protected function clearUnusedShortcodes()
    {
        $this->content = preg_replace([self::PREG_BANNER, self::PREG_TURBO, self::PREG_VOTING], '', $this->content);
    }

    protected function clearEmptyTags()
    {
        $this->content = str_replace(['<div> </div>', '<div></div>', '<mark>', '</mark>', '<p> </p>', '<p></p>', '<p>&nbsp;</p>', '<p itemprop="articleBody"> </p>',  '<p itemprop="articleBody"></p>',''], '', $this->content);
    }

    protected function clearDivWithoutClass()
    {
        try {
            $articleRemoveDiv = new ArticleRemoveDiv();
            $this->content = $articleRemoveDiv->run($this->content);
        } catch (\Throwable $error) {
            \Yii::error('
                Can not remove div from article ' . $this->article->id . ' content ' . $this->content . ' error ' . $error
            );
        }
    }

    protected function setContentNavigation()
    {
        try {
            $contentNavigationWidget = ContentNavigationWidget::widget([
                'article'   => $this->article
            ]);

            $this->content = $contentNavigationWidget . $this->content;
        } catch (\Exception $exception) {
            \Yii::error('Error: setContentNavigation for article ' . $this->article->id . ' ' . $exception);
        }

        return $this;
    }

    protected function addFirstTurboPlace()
    {
        $this->content = InsertBannerShortcodeBehavior::generateShortcode('ad_place_1', true) . $this->content;

        return $this;
    }

    protected function markup()
    {
        $this->markupHeaders($this->content);

        $this->markupParagraphs($this->content);

        $image = $this->markupImage($this->content);
        $image = $this->markupImageLinkTag($image);

        return $this->content . $image;
    }

    protected function markupHeaders(&$content)
    {
        $content = preg_replace('#<h2(.*?)>(.*?)</h2>#', '<h2$1 itemprop="articleSection">$2</h2>', $content);
        $content = preg_replace('#<h3(.*?)>(.*?)</h3>#', '<h3$1 itemprop="articleSection">$2</h3>', $content);
    }

    protected function markupParagraphs(&$content)
    {
        $content = preg_replace('#<p(.*?)>(.*?)</p>#', '<p$1 itemprop="articleBody">$2</p>', $content);
        $content = preg_replace('#<p(.*?)>(.*?)</p>#', '<p itemprop="description">$2</p>', $content, 1);
    }

    protected function markupImage(&$content)
    {
        preg_match_all('#<img.+?src="(.+?)".+?>#', $content, $matches);

        if(isset($matches[0][1])) {
            $secondImage = $matches[0][1];
            $secondImageSrc = $matches[1][1];

            $replace = preg_replace('#(<img.+?)>#', '$1 itemprop="thumbnailUrl">', $secondImage);

            $imageMarkup = <<<HTML
                <div itemscope itemprop="image" itemtype="http://schema.org/ImageObject">
                    <link itemprop="url image" content="{$secondImageSrc}">
                    <meta itemprop="height" content="20">
                    <meta itemprop="width" content="20">
                </div>
HTML;

            $pattern = '#' . preg_quote($secondImage, '#') . '#';
            $replacement = $replace . $imageMarkup;
            $content = preg_replace($pattern, $replacement, $content);
        }
        
    }

    protected function markupImageLinkTag($image)
    {
        preg_match('#href="(.+?)"#', $image,$matches);
        if (sizeof($matches)) {
            return '<link itemprop="image" href="' . $matches[1] . '" />';
        }

        return '';
    }

    protected function prepareAmp()
    {
        $content = $this->content;

        $content = str_replace(["\n"], '', $content);
        $content = preg_replace('#<noindex(.*?)>(.*?)</noindex>#', '$2', $content);
        $content = preg_replace('#<input(.*?)/>#', '', $content);
        $content = preg_replace('#<img (.*?)style(.*?)width: (.*?)px(.*?)height: (.*?)px(.*?)>#', '<amp-img layout="responsive" $1 width="$3" height="$5"></amp-img>', $content);
        $content = preg_replace('#<iframe (.*?)></iframe>#', '<amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive" $1></amp-iframe>', $content);
        
        $this->content = $content;
        return $this;
    }

    public function imageLazyLoading($content)
    {
        preg_match_all('#<img.+?>#', $content, $matches);
        $images = $matches[0];

        foreach ($images as $image) {

            if (preg_match('#class="(.+?)"#', $image, $matches)) {
                $class = $matches[1];

                if (stripos($class, 'youtube__cover') !== false) {
                    continue;
                }
            }

            preg_match('#src="(.+?)"#', $image, $matches);
            if (empty($matches[1])) {
                \Yii::error('Tag img not found attribute src of article id ' . $this->article->id);
                continue;
            }
            $src = $matches[1];

            preg_match('#alt="(.+?)"#', $image, $matches);
            $alt = (isset($matches[1]) && (strpos($matches[1], 'src') === false)) ? $matches[1] : '';

            $pattern = '#' . preg_quote($image, '#') . '#';
            $replacement = '<noscript>' . $image . '</noscript>' .
                '<img data-src="' . $src .'" alt="' . $alt .'" hidden>' .
                '<div class="image__loading" data-alt="' . $alt .'"></div>';

            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }
}
