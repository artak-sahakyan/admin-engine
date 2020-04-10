<?php
namespace common\widgets;

use yii\base\{
    Widget,
    InvalidConfigException
};
use Yii;

class SocialsWidget extends Widget
{
    public $article;
    public $classname = '';
    public $title = '';
    public $socials = [];

    public $baseUrl;

    public function init()
    {
        parent::init();

        $this->baseUrl = 'https://' . Yii::$app->params['currentSiteHost'];

        if ($this->article === null) {
            throw new InvalidConfigException('Article not set');
        }
    }

    public function run()
    {
        $actions = [];

        foreach ($this->socials as $social) {
            $func = 'gen' . $social;
            $actions[$social] = $this->$func();
        }

        $classname = $this->classname;
        $title = $this->title;

        return $this->render('_socials', compact('actions', 'classname', 'title'));
    }


    private function genVk()
    {
        $queryStringVk = http_build_query([
            'url' => $this->baseUrl.$this->article->url,
            'title' => $this->article->title,
            'noparse' => true,
        ]);

        return $this->genWindow('http://vk.com/share.php?' . $queryStringVk);
    }

    private function genOk()
    {
        $queryStringOk = http_build_query([
            'url' => $this->baseUrl.$this->article->url,
            'title' => $this->article->title,
        ]);
        
        return $this->genWindow('https://connect.ok.ru/offer?' . $queryStringOk);
    }

    private function genFacebook()
    {
        $queryStringFacebook = http_build_query([
            'title'     => $this->article->title,
            'u'       => $this->baseUrl.$this->article->url,
            'p[images][0]' => null,
        ]);

        return $this->genWindow('http://www.facebook.com/sharer.php?' . $queryStringFacebook);
    }

    private function genTelegram()
    {
        $queryStringTelegram = http_build_query([
            'url' => $this->baseUrl.$this->article->url,
            'text' => $this->article->title,
        ]);

        return $this->genWindow('https://telegram.me/share/url?' . $queryStringTelegram);
    }

    private function genViber()
    {
        $queryStringViber = http_build_query([
            'text' => $this->baseUrl.$this->article->url,
        ]);

        return $this->genWindow('viber://pa?' . $queryStringViber);
    }

    private function genWA()
    {
        $queryStringWA = http_build_query([
            'text' => $this->baseUrl.$this->article->url
        ]);

        return $this->genWindow('whatsapp://send?' . $queryStringWA);
    }

    private function genWindow($shareUrl) 
    {
        return "window.open('$shareUrl', 'sharer', 'toolbar=0, status=0, width=700, height=400'); return false";
    }
}
