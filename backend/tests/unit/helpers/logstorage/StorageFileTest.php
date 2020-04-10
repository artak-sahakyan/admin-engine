<?php

namespace backend\tests\unit\helpers\logstorage;

use console\helpers\logstorage\LogStorageFile;

class StorageFileTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $sovetsConsole = \Yii::getAlias('@backend') . '/../../sites/sovets.net/console/';
        \Yii::setAlias('@siteConsole', $sovetsConsole);
    }

    protected function _after()
    {
    }

    // tests
    public function testWrite()
    {
        $logCatalog = \Yii::getAlias('@siteConsole') . '/runtime/';
        $storage = new LogStorageFile($logCatalog);
        $storage->init('test-storage-file');
        $logFilePath = $storage->getLogPath();

        $messages = [];
        $messages[] = 'Init log';
        $messages[] = 'log message';

        $storage->write($messages[0]);
        $storage->write($messages[1]);

        $fileContent = file($logFilePath, FILE_IGNORE_NEW_LINES);
        foreach ($fileContent as $lineNumber => $contentLine) {
            $this->assertEquals($messages[$lineNumber], $contentLine);
        }

        // call desctructor
        $storage->final('done');
        $this->assertFileNotExists($logFilePath);
    }
}