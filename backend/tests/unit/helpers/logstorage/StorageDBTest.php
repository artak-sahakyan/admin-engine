<?php

namespace backend\tests\unit\helpers\logstorage;

use backend\models\CronLog;
use console\helpers\logstorage\LogStorageDB;

class StorageDBTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        // connect to db
        // TODO Нужно спрятать конфигурацию БД от гита!
//        var_dump(\Yii::$app->setComponents([
//            'db' => [
//                'class' => 'yii\db\Connection',
//                'dsn' => 'mysql:host=127.0.0.1;dbname=yii2-sovets',
//                'username' => 'project2_freeze',
//                'password' => '887744',
//                'charset' => 'utf8',
//            ]
//        ]));
//        exit;

//        \Yii::$app->db->close(); // make sure it clean
//        \Yii::$app->db->dsn= 'yourdsn';
//        \Yii::$app->db->username = 'project2_freeze';
//        \Yii::$app->db->password = '887744';
//        \Yii::$app->db->charset = 'utf8';
    }

    protected function _after()
    {
    }

    // tests
    public function testWrite()
    {
        // TODO YII MODEL тут должна быть фейковая модель, что бы в основную данные не писать.
        $model = new CronLog;
        $storage = new LogStorageDB($model);
        $cronLogId = $storage->init('test-storage-file');

        $messages = [];
        $messages[] = 'Init log';
        $messages[] = 'log message';

        $storage->write($messages[0]);
        $storage->write($messages[1]);

        $logs = $model->find(['cron_log_id' => $cronLogId])->asArray()->all();
        $i = 0;
        foreach ($logs as $log) {
            $this->assertEquals($messages[$i], $log);
            $i++;
        }


    }
}