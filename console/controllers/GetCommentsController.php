<?php
namespace console\controllers;

use Yii;
use common\models\{ Comment };
use yii\console\ExitCode;
use \console\helpers\Console;

class GetCommentsController extends ConsoleController
{
    public $get_all;
    
    public function options($actionID)
    {
        return ['get_all'];
    }
    
    public function optionAliases()
    {
        return ['p' => 'get_all'];
    }
    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand(); 
        $this->console->output('Получение комментариев');

        $last_comment = Comment::find()->select(['id'])->orderBy('id DESC')->one();

      //  while ($this->get_all || $last_comment) {
           // $new_comments = $this->sendRequest(($last_comment) ? $last_comment->id : null);
            $new_comments = $this->sendRequest(null);
            $last_comment = $new_comments['data']['comment_last_id'];

          //  if(count($new_comments['data']['comments']) == 0) break;

            foreach ($new_comments['data']['comments'] as $new_comment) {

                if(!Comment::findOne($new_comment['id'])) {
                    $comment = new Comment();
                    $comment->id = $new_comment['id'];
                    $comment->message = $new_comment['message'];
                    $comment->ip = $new_comment['ip'];
                    $comment->datеtime = $new_comment['datеtime'];
                    $comment->rating = $new_comment['rating'];
                    $comment->attaches = $new_comment['attaches'];
                    $comment->visible = ($new_comment['visible']) ? 1 : 0;
                    $comment->user_id = $new_comment['user']['id'];
                    $comment->nick = $new_comment['user']['nick'];
                    $comment->name = $new_comment['user']['name'];
                    $comment->email = $new_comment['user']['email'];
                    $comment->phone = $new_comment['user']['phone'];
                    $comment->avatar = $new_comment['user']['avatar'];
                    $comment->chat_id = $new_comment['chat']['id'];
                    $comment->url = $new_comment['chat']['url'];
                    $comment->title = $new_comment['chat']['title'];

                    if($comment->save()) {
                        $this->console->output('Добавлен комментарий ' . $comment->id);
                    } else {
                        die(var_dump($comment->errors));
                        $this->console->output('Ошибка сохранения ' . $comment->id);
                    }
                }
            }
     //   } 

        $this->console->output('Всё загружено');
        
        $this->stopCommand();
        return ExitCode::OK;
    }

    private function sendRequest($last_id = null)
    {
        $curl = curl_init();

        $configs = Yii::$app->params['tolstoycomments'];

        $url = 'https://api.tolstoycomments.com/api/export/' . $configs['key'] . '/site/' . $configs['site_id'] .  '/comment' . ((!empty($last_id)) ? ('/' . $last_id) : null);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);

        if (!is_null(($jresp = json_decode($resp, true)))) {
            $resp = $jresp;
        }

        return $resp;
    }
}
