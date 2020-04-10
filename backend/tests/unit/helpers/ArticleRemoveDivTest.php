<?php namespace backend\tests;

use common\helpers\ArticleRemoveDiv;
use common\models\Article;

class ArticleRemoveDivTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;

    protected $fixtures;
    
    protected function _before()
    {
        $this->fixtures = require codecept_data_dir() . 'article_content.php';
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        // validate
        $content = $this->fixtures['content'];
        $articleRemoveDiv = new ArticleRemoveDiv();
        $valid = $articleRemoveDiv->validate($content);
        $this->assertTrue($valid);

        // collect position
        $mapDiv = $articleRemoveDiv->collectPosition($content);
        $this->assertEquals($this->fixtures['mapDiv'], $mapDiv);

        // prepare $mapDiv(sort and change structure) from mapDiv
        $removePositions = $articleRemoveDiv->prepareMapDiv($mapDiv);

        // remove div
        $withOutDivContent = $articleRemoveDiv->removeDiv($content, $removePositions);
        $this->assertEquals($this->fixtures['removedDivContent'], $withOutDivContent);

        // test run
        $withOutDivContent = $articleRemoveDiv->run($content);
        $this->assertEquals($this->fixtures['removedDivContent'], $withOutDivContent);
    }
}