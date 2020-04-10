<?php
namespace common\widgets;

use Yii;
use common\models\ArticleAdvertisingIntegration;
use yii\base\Widget;

/**
 * Виджет, преобразовывающий шорткод в рабочий элемент
 */
class ShortcodeWidget extends Widget
{
    /**
     * @var Регулярка для получения числа
     */
    const PREG_INT = '/[^0-9]/';
    /**
     * @var Функция для выполнения
     */
    public $template;
    /**
     * @var Входящий массив шорткодов
     */
    public $sources;
    /**
     * @var Статья
     */
    public $article;
    /**
     * @var bool Контент для веба
     */
    public $forWeb;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $func = $this->template;
        return self::$func();
    }

    /**
     * Render sources block
     * @return string
     */
    private function sources()
    {
        $result = [];

        if(isset($this->sources) && !empty($this->sources)) {

            $items = array_filter(explode('[ol ', $this->sources));
            unset($items[0]);

            foreach ($items as $key => $value) {
                preg_match($this->genPregMatch('url'), $value, $url);
                preg_match($this->genPregMatch('title'), $value, $title);
                $result[] = ['title' => $title[1], 'url' => $url[1]];
            }
        }

        return $this->render($this->template, [ 'items' => $result]);
    }

    /**
     * Render video block
     * @return string
     */
    private function videos()
    {
        $result = [];

        if(isset($this->sources) && !empty($this->sources)) {
            $items = array_filter(explode('[youtube ', $this->sources));
            unset($items[0]);

            foreach ($items as $key => $value) {
                preg_match($this->genPregMatch('id'), $value, $id);
                preg_match($this->genPregMatch('title'), $value, $title);
                $result[] = ['title' => $title[1], 'id' => $id[1]];
            }
        }

        return $this->render($this->template, [ 'items' => $result]);
    }

    private function lazyyoutube()
    {
        $result = [];

        if(!empty($this->sources)) {
            preg_match($this->genPregMatch('id'), $this->sources, $id);

            $result = LazyyoutubeWidget::widget([
                'id' => $id[1],
                'forWeb' => $this->forWeb,
            ]);
        }

        return $result;
    }

    /**
     * Render votings
     * @return string
     */
    private function votings()
    {
        $voting = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('id'), $this->sources, $id);

            $voting = VotingWidget::widget([
                'id' => $id[1]
            ]);
        }

        return $voting;
    }

    /**
     * Render seohide link
     * @return string
     */
    private function seohide()
    {
        $link = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('url'), $this->sources, $url);
            preg_match($this->genPregMatch('title'), $this->sources, $title);

            $link = SeohideWidget::widget([
                'title' => $title[1], 
                'url' => $url[1]
            ]);
        }

        return $link;
    }

     /**
     * Render turbo banner
     * @return string
     */
    private function turbo()
    {
        $turboBanner = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('alias'), $this->sources, $alias);

            $turboBanner = TurboBannerWidget::widget([
                'alias' => $alias[1]
            ]);
        }

        return $turboBanner;
    }

    /**
     * Render turbo banner
     * @return string
     */
    private function amp()
    {
        $ampBanner = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('alias'), $this->sources, $alias);

            $ampBanner = BannerWidget::widget([
                'alias'     => $alias[1]
            ]);
        }

        return $ampBanner;
    }

    /**
     * Render review
     * @return string
     */
    private function review()
    {
        $review = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('name'), $this->sources, $name);
            preg_match($this->genPregMatch('content'), $this->sources, $content);

            $review = ReviewWidget::widget([
                'name' => $name[1], 
                'content' => $content[1]
            ]);
        }

        return $review;
    }

    private function related()
    {
        $related = null;

        if(isset($this->sources) && !empty($this->sources)) {
            $title = null;
            if(empty(ArticleWidget::$excludesIds)) {
                $title = 'Статьи по теме';
            }

            $related = ArticleWidget::widget([
                'article'           => $this->article,
                'block_limit'       => 3,
                'title'             => $title
            ]);
        }

        return $related;
    }

    /**
     * Render advertising integration
     * @return string
     */
    private function advertisingIntegration()
    {
        $advertContent = null;

        if(isset($this->sources) && !empty($this->sources)) {
            $id = preg_replace(self::PREG_INT, '', $this->sources);
            $advert = ArticleAdvertisingIntegration::find()
                ->select(['text'])
                ->andWhere(['id' => $id])
                ->andWhere(['is_active' => true])
                ->andWhere([
                    'or', 
                    ['>=','end_date', time()],
                    ['end_date' => null]
                ])
                ->one();
            if(!empty($advert)) {
                $advertContent = $advert->text;
            }
        }

        return $advertContent;
    }

    /**
     * Render banner
     * @return string
     */
    private function banners()
    {
        $bannerHtml = null;

        if(isset($this->sources) && !empty($this->sources)) {    
            preg_match($this->genPregMatch('alias'), $this->sources, $alias);
            preg_match($this->genPregMatch('action'), $this->sources, $action);

            $bannerHtml = BannerWidget::widget([
                'alias'     => $alias[1], 
                'action'    => $action[1],
                'article'   => $this->article
            ]);
        }

        return $bannerHtml;
    }

    private function genPregMatch(string $param)
    {
        return '#' . $param . '="(.+?)"(?=$|\s)#i';
    }
}
