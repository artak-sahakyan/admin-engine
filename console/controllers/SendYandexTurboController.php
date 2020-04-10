<?php
namespace console\controllers;

use Yii;
use yii\console\ExitCode;
use common\helpers\FilesHelper;

class SendYandexTurboController extends ConsoleController
{
    public $sendLastArticles = true;
    private $channelName = 'yandex-turbo';

    public function options($actionID)
    {
        return ['sendLastArticles'];
    }
    
    public function optionAliases()
    {
        return ['last' => 'sendLastArticles'];
    }
    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand();

        $this->send();
        
        $this->stopCommand();
        return ExitCode::OK;
    }

    private function send()
    {
        $configs = Yii::$app->params['yandex'];

        $token =  $configs['turbo_token'];
        $host =  $configs['host_id'];
        $user =  $configs['turbo_user_id']; 

        $this->console->output('Get upload address...');
        $httpClient = new \GuzzleHttp\Client();
        $url = 'https://api.webmaster.yandex.net/v4/user/' . $user . '/hosts/' . $host . '/turbo/uploadAddress';
        $res1 = $httpClient->request('GET', $url, [
            'headers' => [
                'Authorization' => 'OAuth '.$token,
            ]      
        ]);

        if($res1->getStatusCode() != 200) {
            $this->console->output('Error get upload_address');
            $this->console->exitLog();
            exit;
        }

        $upload_address = json_decode($res1->getBody())->upload_address;

        $this->console->output("Set upload address: ".$upload_address);
        $this->console->output("Send rss channels...");

        $site = 'https://' . Yii::$app->params['currentSiteHost'];

        $channelName = $this->channelName . ((!$this->sendLastArticles) ? ('_' . (24-date("H"))) : '' );

        $file = gzencode(file_get_contents($site.'/rss/'.$channelName.'.xml'), 9);

        if(!empty($file)) {
            $this->console->output("Sending last RSS");
            $res = $httpClient->request('POST', $upload_address, [
                'headers' => [
                    'Authorization' => 'OAuth '.$token,
                    'Content-type' => 'application/rss+xml',
                    'Content-Encoding' => 'gzip'
                ],
                'body' =>  $file
            ]);
        }
    }
}
