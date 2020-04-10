<?php

namespace console\controllers;


use yii\console\controllers\MigrateController;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/* for Run global migration need use command yii global-migrate
* for creating global migration need use command yii global-migrate/create
*/

class GlobalMigrateController extends MigrateController
{


    protected function getMigrationNameLimit()
    {
        return static::MAX_NAME_LENGTH;
    }

    public function actionUp($limit = 0)
    {
        if ($res = Console::confirm('Do you want run all available migrations for all your sites ?')) {
            echo "\n\n";
            $sitesFolder = \Yii::getAlias('@sites');
            $sitesPaths = FileHelper::findDirectories($sitesFolder, ['recursive' => false]);
          
            foreach ($sitesPaths as $sitePath) {
                $yii = $sitePath . '/yii';
                $site = basename($sitePath);
               
                echo "\nstarting migration for site $site \n";
                
                if (!file_exists($yii)) {
                    echo "WARNING file $sitePath doesn't exists, migration for site $site will not be run! \n\n";
                    continue;
                }
                
                echo shell_exec("php $yii global-migrate/run $limit"), "\n";
            }
            die("Done \n");
        }
        
        die("Migrations are canceled \n");
    }

    public function actionRun($limit)
    {
       return parent::actionUp($limit);
    }
}
