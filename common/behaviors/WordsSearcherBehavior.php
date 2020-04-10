<?php

namespace common\behaviors;


use \backend\models\Article;
use common\models\ArticleNavigation;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

/**
 * Class WordsSearcher
 *
 * Сервис для получения количества вхождений слов и фраз в тексте статьи
 * Слова и фразы ищутся с учетом морфологии
 *
 */
class WordsSearcherBehavior extends Behavior
{

    /**
     * @var array
     */
    private $wordsFrequency;

    /**
     * @var array
     */
    private $contentBlocks = [];

    private $alts;

    /**
     * Поиск одного слова или фрызы
     *
     * @param $word
     * @param Article $article
     * @return int Количество слов или фраз, встречаемых в статье
     */
    public function searchOne($word, $article)
    {
        $words = ['\b'.$word.'\b'];
        $content = $article->content;
        return $this->countWords($words, $content);
    }

    public function searchMany(array $words, Article $article)
    {
        $result = [];

        foreach ($words as $word) {
            $result[$word] = $this->searchOne($word, $article);
        }

        return $result;
    }

    public function wordsFrequency($content)
    {
        $words = [];
        $frequency = [];

        $content = html_entity_decode(strip_tags($content));

        $words = explode(' ', $content);

        // Убрать знаки припинания
        $words = array_map(
            function ($word) {
                return mb_strtolower(trim(preg_replace('/,|\.|\?|\!|;|:/', '', $word)));
            },
            $words
        );

        $wrongWords = array_merge(self::pretextList(), self::unionsList());

        // Убрать короткие слова, предлоги и союзы
        $words = array_filter(
            $words,
            function ($word) use ($wrongWords) {
                return mb_strlen($word) > 2 && !in_array($word, $wrongWords);
            }
        );



            $words = array_map(function ($word) {
            $word = $this->removeWordEnd($word);

                return $word;
            }, $words);

        $frequency = array_count_values($words);

        return $frequency;
    }

    private function removeWordEnd($word)
    {
        $length = mb_strlen($word);

        if ($length == 4) {
            $word = mb_substr($word, 0, -1);
        } elseif ($length >= 5) {
            $word = mb_substr($word, 0, -2);
        }

        return $word;
    }

    static function unionsList()
    {
        return [
            'благо','буде','будто','вдобавок','дабы','даже','же','едва','ежели',
            'если','зато','зачем','и','ибо','или','кабы','как','как-то','когда',
            'коли','либо','лишь','нежели','но','однако','особенно','оттого',
            'отчего','пока','покамест','покуда','поскольку','потому',
            'почему','притом','причем','пускай','пусть','раз','словно',
            'также','тоже','только','точно','хотя','чем','что','чтоб','чтобы',
        ];
    }

    static function pretextList()
    {
        return [
            'от', 'без', 'у', 'до', 'возле', 'для', 'вокруг', 'по', 'к', 'на',
            'за', 'через', 'про', 'за', 'под', 'над', 'перед', 'с', 'о',
            'в', 'об', 'при', 'обо',
        ];
    }

    /**
     * @param $content
     * @return mixed|string
     */
    protected function normalizeContent($content)
    {
        return mb_strtolower($content, 'utf8');
    }

    /**
     * @param array $words
     * @param $content
     * @return int
     */
    protected function countWords(array $words, $content)
    {
        if (!empty($words)) {
            $title_words_count = 0; //количество слов в заголовках
            $table_words_count = 0; //количество слов в таблицах
            $attr_words = [];       //слова в аттрибутах html тегов
            foreach ($words as $word) {
                $attr_words[] = '\<[^\>]*?=\"[^\"]*?'.$word.'[^\"]*?\"[^\>]\>*?';

                //поиск слов в заголовках
                preg_replace_callback('/\<h[1-3].*?\<\/h[1-3]\>/usi', function ($matches1) use ($word, &$title_words_count) {
                    preg_replace_callback(sprintf('/%s/', $word).'usi', function ($matches2) use (&$title_words_count) {
                        if (!empty($matches2[0])) {
                            $title_words_count++;
                        }
                    }, $matches1[0]);
                }, $content);

                //поиск слов в таблицах
                preg_replace_callback('/\<table.*?\<\/table\>/usi', function ($matches3) use ($word, &$table_words_count) {
                    preg_replace_callback(sprintf('/%s/', $word).'usi', function ($matches4) use (&$table_words_count) {
                        if (!empty($matches4[0])) {
                            $table_words_count++;
                        }

                    }, $matches3[0]);
                }, $content);
            }

            $pre_pattern = implode('|', $words);
            $attr_pre_pattern = implode('|', $attr_words);

            $attr_pattern = sprintf('/%s/', $attr_pre_pattern).'usi';
            $pattern = sprintf('/%s/', $pre_pattern).'usi';

            preg_match_all($pattern, $content, $matches);
            preg_match_all($attr_pattern, $content, $attr_matches);

            return (count($matches[0])-$title_words_count-count($attr_matches[0])-$table_words_count);
        }
    }

    public function analyzeArticleHeadersOnNausea()
    {

        $title = (!empty($this->owner->articleMeta->meta_title)) ? $this->owner->articleMeta->meta_title : $this->owner->title;
        $titleFrequency = $this->wordsFrequency($title);
        $titleRatio = $this->count($titleFrequency);

        $H1Frequency = $this->wordsFrequency($this->owner->title);
        $H1Ratio = $this->count($H1Frequency);

        if(empty($this->owner->articleMeta)) {
            return false;
        }

        $descriptionFrequency = $this->wordsFrequency($this->owner->articleMeta->meta_description);
        $descriptionRatio = $this->count($descriptionFrequency);

        $keywordsFrequency = $this->wordsFrequency($this->owner->articleMeta->meta_keywords);
        $keywordsRatio = $this->count($keywordsFrequency);

        $contentsFrequency = $this->wordsFrequency($this->contents());
        $contentsRatio = $this->count($contentsFrequency);

        $altFrequency = $this->wordsFrequency($this->alt());
        $altRatio = $this->count($altFrequency);

        $textFrequency = $this->wordsFrequency($this->text());
        $textRatio = $this->count($textFrequency);

        arsort($textFrequency);

        return [
            'title' => $titleRatio,
            'description' => (isset($descriptionRatio)) ? $descriptionRatio : 0,
            'keywords' => (isset($keywordsRatio)) ? $keywordsRatio : 0,
            'chapters' => $contentsRatio,
            'h1' => $H1Ratio,
            'alt' => $altRatio,
            'text' => $textRatio,
            'wordsFrequency' => $textFrequency,
        ];
    }

    private function contents()
    {
        $headersArray = [];
        $structure = $this->getArticleChapters();

        foreach ($structure as $structureItem) {
            $headersArray[] = $structureItem->label;

            if (!isset($structureItem->childs)) {
                continue;
            }

            foreach ($structureItem->childs as $child) {
                $headersArray[] = $child->label;
            }
        }

        $headersString = implode(' ', $headersArray);

        return $headersString;
    }

    private function alt()
    {
        $searchString = '';
        preg_match_all('/<img.?alt="(.+?)"/', $this->owner->content, $matches);

        if (isset($matches[1])) {
            $searchString = implode(' ', $matches[1]);
        }

        return $searchString;
    }

    private function text()
    {
        $content = strip_tags($this->owner->content);
        $content = preg_replace('/\.|:|;|\?|!|,|\)|\(/', '', $content);
        $content = preg_replace('/\n/', ' ', $content);

        return $content;
    }

    private function count($array)
    {
        $value = 0;

        if (is_array($array) && count($array) >= 1) {
            arsort($array);
            $value = (int)(array_shift($array));
            $value = $value != 0 ? round(sqrt($value), 2) : 0;
        }

        return $value;
    }


    /**
     * Get data from MarkuperBehavior::class
     *
     * @return mixed
     */
    public function getArticleChapters()
    {

        $articleNavigation = $this->owner->articleNavigation;

        if (!$articleNavigation || !$articleNavigation->text) {
            $this->owner->attachBehaviors([MarkuperBehavior::class]);
            $this->owner->generateNavigationStructure();
        }

        return $articleNavigation->text();
    }


    public function generateHeader()
    {
        $this->setArticleContentBlock();

        $this->setWordsFrequency();




        usort($this->contentBlocks, function($a, $b)
        {
            return strcmp($a['name'], $b['name']);
        });

       $last = array_pop($this->contentBlocks);
       array_unshift($this->contentBlocks, $last);

        if ($this->wordsFrequency && current($this->wordsFrequency) == 1) {
            return $this->contentBlocks; // Если все слова по 1му - ничего не подсвечиваем
        }

        // Получение самого частого слова
        $word = key($this->wordsFrequency);

        $length = mb_strlen($word);

        if ($length == 4) {
            $word = mb_substr($word, 0, -1);
        } elseif ($length >= 5) {
            $word = mb_substr($word, 0, -2);
        }

        foreach ($this->contentBlocks as $key => $block) {

            if (is_array($block)) {
                foreach ($block as $blockKey => $blockValue) {
                    $block[$blockKey] = $this->addBacklight($word, $blockValue);
                }
            } else {
                $block = $this->addBacklight($word, $block);
            }

            $this->contentBlocks[$key] = $block;

        }

        return $this->contentBlocks;
    }

    private function setWordsFrequency()
    {
        $text = '';

        foreach ($this->contentBlocks as $block) {
            if (is_array($block)) {
                $text .= ' ' . implode(' ', $block);
            } else {
                $text .= ' ' . $block;
            }
        }

        $text = strip_tags($text);
        $text = preg_replace('/\.|:|;|\?|!|,|\)|\(/', '', $text);
        $text = preg_replace('/\n/', ' ', $text);

        $textFrequency = $this->wordsFrequency($text);
        arsort($textFrequency);
        $this->wordsFrequency = $textFrequency;
    }

    private function setArticleContentBlock()
    {
        $title = (!empty($this->owner->articleMeta->meta_title)) ? $this->owner->articleMeta->meta_title : $this->owner->title;
        $this->contentBlocks[] = [
            'value' => $title,
            'name' => 'title',
            'symbols' => mb_strlen(strip_tags($title))
        ];

        $this->contentBlocks[] = [
            'value' => $this->owner->title,
            'name' => 'h1',
            'symbols' => mb_strlen(strip_tags($this->owner->title))
        ];

        $this->setHeaders();
    }


    private function addBacklight($word, $text)
    {
        $originalText = $text;

        $compareWord = mb_strtoupper($word);
        $compareText = mb_strtoupper($text);

        $originalTextWords = explode(' ', $originalText);
        $compareTextWords = explode(' ', $compareText);

        foreach ($compareTextWords as $key => $wordItem) {
            if (!$wordItem || !$compareWord) {
                continue;
            }
            $match =  mb_stripos($wordItem, $compareWord);
            if ($match !== false) {
                $originalTextWords[$key] = "<strong>" . $originalTextWords[$key] . "</strong>";
            }

        }

        $colored = implode(' ', $originalTextWords);

        return $colored;
    }


    private function setHeaders()
    {
        preg_match_all("/<h[2|3]>(.*)<\/h[2|3]>/", $this->owner->content, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $key => $match) {
                $name = strpos($matches[0][$key], 'h2') ? 'h2' : 'h3';
                $this->contentBlocks[] = [
                    'value' => $match,
                    'name' => $name,
                    'symbols' => mb_strlen(strip_tags($match))

                ];
            }
        }
    }

    public function generateAlts()
    {
        $this->setArticleAlts();

        if (!$this->alts) {
            return [];
        }

        $altsText = implode(' ', ArrayHelper::getColumn($this->alts, 'alt'));
        $frequency = $this->wordsFrequency($altsText);

        arsort($frequency);

        if ($frequency && current($frequency) == 1) {
            return $this->alts; // Если все слова по 1му - ничего не подсвечиваем
        }

        $word = key($frequency);

        $length = mb_strlen($word);

        if ($length == 4) {
            $word = mb_substr($word, 0, -1);
        } elseif ($length >= 5) {
            $word = mb_substr($word, 0, -2);
        }

        foreach ($this->alts as $key => $block) {

            if (is_array($block)) {
                foreach ($block as $blockKey => $blockValue) {
                    $block[$blockKey] = $this->addBacklight($word, $blockValue);
                }
            } else {
                $block = $this->addBacklight($word, $block);
            }

            $this->alts[] = $block;
        }

        return $this->alts;
    }


    private function setArticleAlts()
    {
        preg_match_all('/alt="([^"]*+)"/', $this->owner->content, $matches);

        if (!isset($matches[1])) {
            $this->alts = [];
        } else {
            foreach ($matches[1] as $key => $alt) {
                $this->alts[$key] = [
                    'alt' => $alt,
                    'symbols' => mb_strlen(strip_tags($alt))
                ];
            }
        }
    }


}