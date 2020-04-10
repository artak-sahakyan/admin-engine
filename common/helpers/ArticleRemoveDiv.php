<?php

namespace common\helpers;


class ArticleRemoveDiv
{
    /**
     * Execute all steps
     * @param $content
     * @return string
     */
    public function run($content)
    {
        // validation
        $valid = $this->validate($content);
        if (!$valid) {
            throw new \Error('Content is not valid');
        }

        // collect position of div without class
        $mapDiv = $this->collectPosition($content);
        // if content have not div
        if (sizeof($mapDiv) == 0) {
            return $content;
        }

        // prepare $mapDiv(sort and change structure) from mapDiv
        $removePositions = $this->prepareMapDiv($mapDiv);

        // remove div without class
        $contentWithoutDiv = $this->removeDiv($content, $removePositions);

        return $contentWithoutDiv;
    }

    /**
     * Check is valid removed tag 'div'
     * @param $content
     * @return bool
     */
    public function validate($content)
    {
        preg_match_all('~</?div~i', $content, $matches);
        $stack = [];
        $valid = true;
        foreach ($matches[0] as $match) {
            if ($match == '<div') {
                $stack[] = $match;
            } else if ($match == '</div') {
                if (sizeof($stack) == 0) {
                    $valid = false;
                } else {
                    array_pop($stack);
                }
            }
        }
        if (sizeof($stack) > 0) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * Collect position removed tag 'div' have attribute class
     * @param $content
     * @return array
     */
    public function collectPosition($content)
    {
        $mapDiv = [];

        preg_match_all('~<div(?:.*?)>|</div>~i', $content, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $key => $match) {
            if (preg_match('~<div~', $match[0]) && preg_match('~class~', $match[0]) === 0) {
                $cutContentStart = $match[1];
                $cropContent = substr($content, $cutContentStart);
                $cutContentLength = $cutContentStart;

                $openDivStart = $match[1];
                $openDivLength = strlen($match[0]);

                preg_match_all('~</?div~i', $cropContent, $matchess, PREG_OFFSET_CAPTURE);
                $stack = [];
                foreach ($matchess[0] as $keyy => $match) {
                    if ($keyy == 0) {
                        continue;
                    }

                    if ($match[0] == '<div') {
                        $stack[] = $match[0];
                    } else if ($match[0] == '</div') {
                        if (sizeof($stack) == 0) {
                            $closeDivStart = $cutContentLength + $match[1];
                            $closeDivLength = strlen($match[0]) + 1;

                            $mapDiv[] = [
                                'open' => ['start' => $openDivStart, 'length' => $openDivLength],
                                'close' => ['start' => $closeDivStart, 'length' => $closeDivLength],
                            ];

                            break;
                        } else {
                            array_pop($stack);
                        }
                    }
                }
            }
        }

        return $mapDiv;
    }

    /**
     * Prepare collected position from method collectPosition and changed and sorting structure.
     * @param $mapDiv
     * @return array
     */
    public function prepareMapDiv($mapDiv)
    {
        $removePositions = [];
        $starts = [];
        foreach ($mapDiv as $div) {
            $start = $div['open']['start'];
            $length = $div['open']['length'];

            $starts[] = $start;
            $removePositions[] = ['start' => $start, 'length' => $length];

            $start = $div['close']['start'];
            $length = $div['close']['length'];

            $starts[] = $start;
            $removePositions[] = ['start' => $start, 'length' => $length];
        }
        array_multisort($removePositions, $starts, SORT_NUMERIC);

        return $removePositions;
    }

    /**
     * Collect content without tag 'div' have attribute class
     * @param $content
     * @param $removePositions
     * @return bool|string
     */
    public function removeDiv($content, $removePositions)
    {
        $contentWithoutDiv = substr($content, 0, $removePositions[0]['start']);

        foreach ($removePositions as $key => $position) {
            if (isset($removePositions[$key + 1])) {
                $contentWithoutDiv .= substr($content, $position['start'] + $position['length'], $removePositions[$key + 1]['start'] - $position['start'] - $position['length']);
            } else {
                $contentWithoutDiv .= substr($content, $position['start'] + $position['length']);
            }
        }

        return $contentWithoutDiv;
    }
}