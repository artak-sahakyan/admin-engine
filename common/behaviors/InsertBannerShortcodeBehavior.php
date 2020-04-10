<?php
namespace common\behaviors;

use common\widgets\InsertShortcodeIntoParagraphWidget;
use yii\base\Behavior;

class InsertBannerShortcodeBehavior extends Behavior
{
    const PREG_VIDEO    = '~(<iframe[^>]+>.*?<\/iframe>)~is';
    const PREG_IMG      = '~(<img[^>]+>)~i';
    const PREG_H1       = '~(<h1[^>]+>.*?<\/h1>)~is';

    private $content;

    public function insertTurboBannerShortcodes()
    {
        if($this->loopSetBanners(3) < 2) {
            if($this->loopSetBanners(2) < 2) {
                $this->loopSetBanners(1);
            }
        }

        $this->owner->content = $this->owner->content . self::generateShortcode('ad_place_10', true);

        return $this->owner;
    }

    private function loopSetBanners($length)
    {
        $j = 2;
        $count = 0;

        for ($i=$length; $i <= $length*13; $i+=$length) { 
            if(self::setAfterParagraph('ad_place_' . $j++, $i, null, true)) $count++;
        }

        return $count;
    }


    public function insertBannerShortcodes()
    {
        $this->content = $this->owner->content;

        self::setAfterParagraph('article_after_first_paragraph', 0);
        self::setAfterParagraph('article_in_the_middle', 0, 1/2);
        self::setAfterElement('article_under_video', self::PREG_VIDEO, 100);
        self::setRelatedBlock('article_related_1', 2, null);
        self::setRelatedBlock('article_related_2', 0, 1/3);
        self::setRelatedBlock('article_related_3', 0, 2/3);

        return $this->owner;
    }

    public function setDopBannerShortcodes()
    {
        $this->content = $this->owner->content;

        self::setAfterElement('article_under_first_img', self::PREG_IMG, 0);
        self::setAfterElement('article_under_second_img', self::PREG_IMG, 1);
        self::setAfterElement('article_under_third_img', self::PREG_IMG, 2);
        self::setAfterElement('article_under_last_img', self::PREG_IMG, 100);

        return $this->owner;
    }

    protected function setRelatedBlock(string $place, int $paragraphId = 0, float $adaptiveIndex = null, bool $turbo = false)
    {
        $content = $this->owner->content;
        try {
            $banner = self::generateShortcode($place, $turbo);
            
            $this->owner->content = InsertShortcodeIntoParagraphWidget::widget([
                'content'       => $content, 
                'bannerHtml'    => $banner, 
                'paragraphId'   => $paragraphId, 
                'adaptiveIndex' => $adaptiveIndex
            ]);

        } catch (\Exception $exception) {
            $this->owner->content = $content;
        }

        return $this; 
    }

    public function deleteBannerShortcodes()
    {
        $content = preg_replace('/\[banner(.+?)\]/im', '', $this->owner->content);
        $content = preg_replace('/&nbsp;|<mark><\/mark>|<div><p><mark><\/mark><\/p><\/div>|<p><mark><mark><\/mark><\/mark><\/p>|<p><\/p>/', '', $content);

        $this->owner->content =  $content;
        return $content;
    }

    public function deleteTurboBannerShortcodes()
    {
        $content = preg_replace('/\[turbo(.+?)\]/', '', $this->owner->content);

        $this->owner->content =  $content;
        return $content;
    }

    /**
     * @param string $place
     * @param int $paragraphId
     * @param float $adaptiveIndex
     * @return null|string
     */
    protected function setAfterParagraph(string $place, int $paragraphId = 0, float $adaptiveIndex = null, bool $turbo = false) {

        $content = $this->owner->content;
        try {

            $banner = self::generateShortcode($place, $turbo);

            $this->owner->content = InsertShortcodeIntoParagraphWidget::widget([
                'content'       => $content, 
                'bannerHtml'    => $banner, 
                'paragraphId'   => $paragraphId, 
                'adaptiveIndex' => $adaptiveIndex
            ]);

            return (($content != $this->owner->content) ? true : false);

        } catch (\Exception $exception) {
            $this->owner->content = $content;
        }

        return $this;
    }

    /**
     * @param string $place
     * @param string $pregRule
     * @param int $elementIndex
     * @return null|string
     */
    protected function setAfterElement(string $place, string $pregRule, int $elementIndex, bool $turbo = false)
    {
        try {

            $banner = self::generateShortcode($place, $turbo);

            $content = $this->owner->content;
            preg_match_all($pregRule, $content, $matches);

            $elementIndex = ($elementIndex > count($matches[0])-1) ? count($matches[0])-1 : $elementIndex;

            $this->owner->content = isset($matches[0][$elementIndex])
                ? str_replace($matches[0][$elementIndex], $matches[0][$elementIndex] . $banner, $content)
                : $content;

        } catch (\Exception $exception) {
            $this->owner->content = $content;
        }
        return $this;
    }

    /**
     * @param string $alias
     * @return string
     */
    public static function generateShortcode($alias, $turbo = false)
    {
        if(!$turbo) {
            $shortcode = '[banner alias="' . $alias . '" action="place"]';
        } else {
            $shortcode = '[turbo alias="' . $alias . '"]';
        }
        
        return $shortcode;
    }

}
