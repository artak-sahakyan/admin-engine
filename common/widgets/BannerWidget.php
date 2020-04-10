<?php
namespace common\widgets;

use Yii;
use common\behaviors\GetBannersBehavior;

use common\models\{
    ArticleRelatedYandex,
    Banner,
    BannerDevice
};

use yii\base\Widget;

/**
 * Виджет, который выводит баннер в заданное баннерное место, для заданной статьи
 */
class BannerWidget extends Widget
{
    /**
     * @var Тип баннера
     */
    const DEFAULT_ACTION = 'place';
    public $action;
    /**
     * @var Баннер
     */
    public $banner;
    /**
     * @var Алиас баннерного места
     */
    public $alias;
    /**
     * @var Статья, в которую помещается баннер
     */
    public $article;
    /**
     * @var Заголовок блока
     */
    private $title;
    /**
     * @var Конечный код баннера, вместе с контейнером
     */
    private $bannerCode;
    /**
     * @var Баннерная группа статьи
     */
    private $bannerGroup;
   

    public function behaviors()
    {
        return [
            GetBannersBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        if(isset($this->article) && isset($this->article->bannerGroup)) {
            $this->bannerGroup = $this->article->bannerGroup;
        }

        if($this->alias == 'article_related_1') {
            $this->title = 'Статьи по теме';
        }

        if(empty($this->action)) {
            $this->action = self::DEFAULT_ACTION;
        }
    }

    public function run()
    {
        if(empty($this->banner)) {
            $this->banner = $this->getOneBanner($this->alias, $this->bannerGroup);
        }

        if(!empty($this->banner)) {
            $this->bannerCode = $this->banner->getCode();

            if($this->action == self::DEFAULT_ACTION && $this->bannerCode) {
                $this->bannerCode = $this->injectPlace();
            }
        }

        return $this->bannerCode;
    }

    /**
     * @return string
     */
    private function injectPlace()
    {
        $similarArticles = (($this->checkIsRelatedBlock() && $this->article) ? $this->getSimilarArticles() : null);

        return $this->render('banner', [
            'bannerCode' => $this->bannerCode, 
            'similarArticles' => $similarArticles,
            'alias' => $this->alias
        ]);
    }

    /**
     * @param int $countArticles
     * @return view
     */
    private function getSimilarArticles(int $countArticles = 3)
    {
        return ArticleWidget::widget([
            'article'           => $this->article,
            'block_limit'       => $countArticles,
            'title'             => $this->title
        ]);
    }

    /**
     * @return int|bool
     */
    private function checkIsRelatedBlock()
    {
        return stripos($this->alias, 'related');
    }
}
