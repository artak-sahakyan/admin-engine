<?php

namespace common\behaviors;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Behavior;
use yii\base\Exception;
use yii\httpclient\Client;
use yii\web\HttpException;


/**
 * Class Parser
 *
 * Парсит текст на наличие якорей содержания и возвращает массив,
 * представляющий структуру содержания
 *
 * [
 *    [
 *       'label' => 'Test',
 *       'href' => 'h2_1',
 *       'childs' => [
 *           ...
 *       ]
 *    ]
 * ]
 *
 * @package Core\Domain\Article\Contents
 */
class ParserBehavior extends Behavior
{

    const END_POINT = 'https://olmiweb.com/badenyze.php';

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct()
        {
        $this->crawler = new Crawler();
    }

    /**
     * @param $content
     * @return array
     */
    public function parse($content)
    {
        $contentStructure = [];

        $this->crawler->clear();

        $this->crawler->addHtmlContent($content);

        /*** @var $anchor \DOMElement **/
        foreach($this->crawler->filter('a') as $anchor) {
            if(!$anchor->hasAttribute('name')) {
                continue;
            }

            list($h2Index, $h3Index) = $this->getHeadersIndex($anchor->getAttribute('name'));

            if(!$h2Index) {
                continue;
            }

            if(!$h3Index) {
                $contentStructure[$h2Index] = [
                    'label' => $this->crawler->filter('h2')->eq($h2Index - 1)->text(),
                    'href' => 'h2_' . $h2Index,
                ];
            } else {
                $expression = "//h2[{$h2Index}]/following-sibling::h3[{$h3Index}]";

                if($node = $this->crawler->filterXPath($expression)->getNode(0)) {
                    $contentStructure[$h2Index]['childs'][$h3Index] = [
                        'label' => $node->nodeValue,
                        'href' => 'h2_' . $h2Index . '_h3_' . $h3Index,
                    ];
                }
            }
        }

        return $contentStructure;
    }


    /**
     * @param $href
     * @return array|bool
     */
    private function getHeadersIndex($href)
    {
        if(preg_match('/^h2_(\d+)$/', $href, $matches)) {
            return [$matches[1], false];
        } elseif(preg_match('/^h2_(\d+)_h3_(\d+)$/', $href, $matches)) {
            return [$matches[1], $matches[2]];
        } else {
            return [false, false];
        }
    }

    /**
     * For parsing html from
     */
    public function parseHtml($content)
    {

        $DTO = null;

        $this->crawler->addContent($content, 'text/html');

        $classicalNausea = $this->classicalNausea();
        $academicNausea = $this->academicNausea();

        $density = $this->density();

        $bigram = $this->bigram();

        $trigram = $this->trigram();

        $badenPoints = $this->badenPoints($content);

        return [
            'academicNausea'    => $academicNausea,
            'classicalNausea'   => $classicalNausea,
            'density'           => $density,
            'bigram'            => $bigram,
            'trigram'           => $trigram,
            'badenPoints'       => $badenPoints
        ];
    }

    private function normalizeData($string)
    {
        $string = str_replace('%', '', $string);
        $string = str_replace(' ', '', $string);

        $string = trim($string);

        return floatval($string);
    }

    private function badenPoints($content)
    {
        preg_match_all('/(document\.getElementById\("spamscore"\)\.innerHTML.+>)([0-9]+)%/', $content, $matches);

        $text = isset($matches[2][0]) ? $matches[2][0] : 0;

        return $this->normalizeData($text);
    }

    /**
     * @return string
     */
    private function academicNausea()
    {
        $test = $this->crawler->filter('table')->eq(1)->filter('tr')->eq(1)->filter('td')->eq(1)->filter('span')->text();
        return $this->normalizeData($test);
    }

    /**
     * @return string
     */
    private function classicalNausea()
    {
        $text = $this->crawler->filter('table')->eq(1)->filter('tr')->eq(2)->filter('td')->eq(1)->filter('span')->text();
        return $this->normalizeData($text);
    }

    /**
     * @return array
     */
    private function density()
    {
        $density = [];

        $this->crawler->filter('table')->eq(2)->filter('tr')->each(function (Crawler $node, $i) use (&$density) {
            if ($i != 0) {
                $density[] = $this->normalizeData($node->filter('td')->eq(2)->filter('span')->text());
            }
        });

        return $density;
    }

    /**
     * @return array
     */
    private function bigram()
    {
        $bigramTable1 = [];
        $bigramTable2 = [];

        $this->crawler->filter('table')->eq(3)->filter('tr')->each(function (Crawler $node, $i) use (&$bigramTable1) {
            if ($i != 0) {
                $bigramTable1[] = $this->normalizeData($node->filter('td')->eq(2)->text());
            }
        });

        $this->crawler->filter('table')->eq(5)->filter('tr')->each(function (Crawler $node, $i) use (&$bigramTable2) {
            if ($i != 0) {
                $bigramTable2[] = $this->normalizeData($node->filter('td')->eq(2)->text());
            }
        });

        return array_merge($bigramTable1, $bigramTable2);
    }

    /**
     * @return array
     */
    private function trigram()
    {
        $trigramTable1 = [];
        $trigramTable2 = [];

        $this->crawler->filter('table')->eq(4)->filter('tr')->each(function (Crawler $node, $i) use (&$trigramTable1) {
            if ($i != 0) {
                $trigramTable1[] = $this->normalizeData($node->filter('td')->eq(2)->text());
            }
        });

        $this->crawler->filter('table')->eq(6)->filter('tr')->each(function (Crawler $node, $i) use (&$trigramTable2) {
            if ($i != 0) {
                $trigramTable2[] = $this->normalizeData($node->filter('td')->eq(2)->text());
            }
        });

        return array_merge($trigramTable1, $trigramTable2);
    }

    public function isSatisfiedBy(Array $analysisDTO)
    {
        if ($analysisDTO['classicalNausea'] >= 5.5
            || $analysisDTO['academicNausea'] >= 8
            || !$this->checkCondition($analysisDTO['density'], 2.5)
            || !$this->checkCondition($analysisDTO['bigram'], 4)
            || !$this->checkCondition($analysisDTO['trigram'], 2)
        ) {
            return false;
        }

        return true;
    }


    private function checkCondition(array $params, $limit)
    {
        foreach ($params as $parameter) {
            if ($parameter >= $limit) {
                return false;
            }
        }

        return true;
    }

    public function analysisArticleContent($url)
    {

        $url = 'https://' . \Yii::$app->params['currentSiteHost'] . parse_url($url)['path'];

        $client = new Client();
        try{
            $response = $client->get(self::END_POINT, ['link' => $url])->send();
        } catch (\Exception $exception) {
           // throw new HttpException(503, $exception->getMessage());
            return false;
        }

        $html = $response->content;

        try {
            $analysisDTO = $this->parseHtml($html);
        } catch (\Exception $e) {
            return false;
        }


        $notSpammed = $this->isSatisfiedBy($analysisDTO);
        $analysisDTO['notSpammed'] = $notSpammed;

        return $analysisDTO;
    }

}