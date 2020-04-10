<?php


namespace common\behaviors;


use common\models\ArticleNavigation;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Behavior;

class MarkuperBehavior extends Behavior
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var int
     */
    private $h2Index;

    /**
     * @var int
     */
    private $h3Index;

    public function __construct()
    {
        $this->crawler = new Crawler();
    }

    /**
     * Markup navigation tags and get and save it.
     *
     * @return ArticleNavigation
     */
    public function generateNavigationStructure() {
        $markup = $this->markup();
        $this->owner->content = $markup;

        $this->owner->save(false);

        $articleNavigation = $this->getArticleNavigation();

        $structureNavigation = $this->parseNavigationHeader($this->owner->content);
        $articleNavigation->text = json_encode($structureNavigation);
        $articleNavigation->save();

        return $articleNavigation;
    }

    /**
     * Create anchors for article content.
     *
     * @param $content
     * @return mixed
     */
    private function markup($content=null)
    {
        if(!$content) {
            $content = $this->owner->content;
        }


        $this->crawler->clear();

        $this->crawler->addHtmlContent($content);

        $this->removeContentsAnkors();
        $this->removeContentParagraph();

        $this->h2Index = 0;
        $this->h3Index = 0;

        /*** @var $header \DOMElement **/
        foreach($this->crawler->filter('h2, h3') as $index => $header) {
            /** @var \DOMElement $node */
            $node = $header->ownerDocument->createElement('a');
            $node->setAttribute('name', $this->anchorName($header));

            $header->parentNode->insertBefore($node, $header);
        }

        return trim(str_replace(['<body>', '</body>'], ['', ''], $this->crawler->html()));
    }

    /**
     * Return anchor name.
     *
     * @param \DOMElement $header
     * @return string
     */
    private function anchorName(\DOMElement $header)
    {
        if($header->nodeName == 'h2') {
            $this->h2Index = $this->h2Index + 1;
            $this->h3Index = 0;
            $anchorName = 'h2_' . $this->h2Index;
        } else {
            $this->h3Index = $this->h3Index + 1;
            $anchorName = 'h2_' . $this->h2Index . '_h3_' . $this->h3Index;
        }

        return $anchorName;
    }

    /**
     * Remove all content anchors.
     */
    private function removeContentsAnkors()
    {
        foreach($this->crawler->filter('a') as $a) {
            if($a->hasAttribute('name') AND $a->textContent == '') {
                $a->parentNode->removeChild($a);
            }
        }
    }

    /**
     * Remove empty tag p from content.
     */
    private function removeContentParagraph()
    {
        foreach($this->crawler->filter('p') as $p) {
            if($p->textContent == '' && $p->getElementsByTagName('*')->count() == 0) {
                $p->parentNode->removeChild($p);
            }
        }
    }

    /**
     * Parse article content and return navigation header.
     *
     * @param $content
     * @return array
     */
    private function parseNavigationHeader($content)
    {
        $navigationHeader = [];

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
                $navigationHeader[$h2Index] = [
                    'label' => $this->crawler->filter('h2')->eq($h2Index - 1)->text(),
                    'href' => 'h2_' . $h2Index,
                ];
            } else {
                $expression = "//h2[{$h2Index}]/following-sibling::h3[{$h3Index}]";

                if($node = $this->crawler->filterXPath($expression)->getNode(0)) {
                    $navigationHeader[$h2Index]['childs'][$h3Index] = [
                        'label' => $node->nodeValue,
                        'href' => 'h2_' . $h2Index . '_h3_' . $h3Index,
                    ];
                }
            }
        }

        return $navigationHeader;
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
     * Get or create record.
     *
     * @return ArticleNavigation
     */
    private function getArticleNavigation()
    {
        return $this->owner->articleNavigation ?: new ArticleNavigation(['article_id' => $this->owner->id]);
    }
}