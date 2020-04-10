<?php

namespace console\controllers;


use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/*
for Run global  command yii global-command
*/

class GlobalCommandController extends Controller
{


    public function actionIndex($command)
    {
        if (Console::confirm("Do you want run command --- $command --- for all your sites ?")) {
            echo "\n\n";
            $sitesFolder = \Yii::getAlias('@sites');
            $sitesPaths = FileHelper::findDirectories($sitesFolder, ['recursive' => false]);


            foreach ($sitesPaths as $sitePath) {
                $yii = $sitePath . '/yii';
                $site = basename($sitePath);

                echo "\nstarting command for site $site \n";
                if (!file_exists($yii)) {
                    echo "WARNING file $sitePath doesn't exists, command for site $site will not be run! \n\n";
                    continue;
                }
                echo shell_exec("php $yii $command"), "\n";
            }
            die("Done \n");

        }
        die("Commands are canceled \n");
    }






}