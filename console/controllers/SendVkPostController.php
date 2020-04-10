<?php
namespace console\controllers;

use Yii;
use common\models\{ Article, ArticleSocial };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use VK\Client\VKApiClient;
use common\helpers\FilesHelper;
use yii\helpers\Url;

class SendVkPostController extends ConsoleController
{

    /**
     * @param null $id
     */
    public function actionIndex($article_id = null)
    {
        $this->startCommand();

        if(!$article_id) {
            $article = Article::find()
                ->joinWith('articleSocial')
                ->andWhere([
                    'or', 
                    ['sended_vk' => null],
                    ['sended_vk' => 0]
                ])
                ->andWhere(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', (time() + 60 * 60 * 3)]])
                ->orderBy('id')
                ->limit(1)
                ->one();

        } else {
            $article = Article::findByKey($article_id);
        }

        if(!$article) {
            $this->console->output('Нет статей для отправки');
        } else {
            $this->console->output('Отправка статьи ' . $article->id . ' в ВК');

            $sendStatus = self::processArticle($article);

            if ($sendStatus) {
                $this->console->output('Статья отправлена');
            } else {
                $this->console->output('Ошибка отправки статьи');
            }
        }

        $this->stopCommand();
        return ExitCode::OK;
    }

    private function processArticle($article)
    {
        $vk = new VKApiClient('5.92');

        $configs = Yii::$app->params['vkcom'];

        if(empty($configs['access_token']) || empty($configs['group_id'])) {
            $this->console->output('Не установлен access_token или group_id');
            return false;
        }

        $response = $vk->wall()->post($configs['access_token'], [
            'owner_id' => $configs['group_id'],
            'from_group' => 1,
            'message' => $article->title,
            'attachments' => Url::base(true) . $article->getUrl()
        ]);

        $this->console->endProgress();

        $articleSocial = $article->articleSocial;
        if(!$articleSocial) {
            $articleSocial = new ArticleSocial();
            $articleSocial->article_id = $article->id;
            $articleSocial->save();
        }

        if($response['post_id']) { 
            $articleSocial->sended_vk = 1;
            $articleSocial->save();
        } 

        return $articleSocial->sended_vk;
    }
}
