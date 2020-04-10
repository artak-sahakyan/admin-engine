<?php
namespace console\controllers;

use common\models\{ RssChannel };
use yii\console\ExitCode;
use common\helpers\RssHelper;

class GenerateRssController extends ConsoleController
{
    public $lastDays = 2;

    public function options($actionID)
    {
        return ['lastDays'];
    }
    
    public function optionAliases()
    {
        return ['ld' => 'lastDays'];
    }

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '2024M');
        ini_set('max_execution_time', 0);

        $this->startCommand();

        $countFiles = self::processChannels();
        
        $this->console->output('RSS каналы созданы (' . $countFiles . ' файлов)');

        $this->stopCommand();
        return ExitCode::OK;
    }

    private function processChannels()
    {
        $rssChannels = RssChannel::find();
        $countRssChannels = $rssChannels->count();

        $this->console->output('Всего ' . $countRssChannels . ' каналов');

        $counterProgress = 1;
        $countFiles = 0;
        $time = time();
        $this->console->startProgress($counterProgress, $countRssChannels);

        foreach ($rssChannels->each(100) as $rssChannel) {
            
            $countFiles += RssHelper::generate($rssChannel, $this->lastDays);
        
            $this->console->updateProgress($counterProgress++, $countRssChannels);
            
        }

        $this->console->endProgress();

        return $countFiles;
    }
}
