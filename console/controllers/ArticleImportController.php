<?php

namespace console\controllers;

use common\helpers\UrlHelper;
use common\models\Admin;
use common\models\ArticleAnchor;
use common\models\ArticleMeta;
use console\helpers\Console;
use Yii;
use common\models\Article;
use yii\console\Controller;
use yii\console\ExitCode;

class ArticleImportController extends ConsoleController
{
    public $readOnly = true;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'readOnly',
        ]);
    }

    public function actionIndex()
    {
        ini_set('max_execution_time', 0);

        $this->startCommand();

        if ($this->readOnly) {
            $this->console->output('Script running is readonly mode');
        }

        // get recent performed
        $response = $this->sendRequest('tasks/getRecentPerformed', ['withPoster' => 1]);
        if ($response['result'] != 'success') {
            $this->console->output(var_export($response, true));
            return ExitCode::OK;
        }
        $this->console->output('Found ' . sizeof($response['tasks']) . ' tasks');
        $taskPerformed = [];
        foreach ($response['tasks'] as $task) {
            $taskPerformed[$task['id']] = $task['title'];

            $this->console->output('TaskId ' . $task['id'] . ' Title "' . $task['title'] .'"');
        }



        // build term
        $titles = [];
        foreach ($taskPerformed as $title) {
            $titles[] = Yii::$app->db->quoteValue($title);
        }

        // exclude isset articles
        $terms = [];
        $terms['title'] = implode(", ", $titles);
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `id`, `title` FROM `articles` WHERE `title` IN (" . $terms['title'] . ")
        ");
        $reader = $command->query();
        while ($row = $reader->read()) {
            if (false !== ($foundKey = array_search($row['title'], $taskPerformed))) {
                unset($taskPerformed[$foundKey]);
                $this->console->output('Skip isset task with title "' . $row['title'] . '"');
            }
        }
        $this->console->output('Total add new tasks ' . sizeof($taskPerformed));

        foreach ($taskPerformed as $taskId => $title) {

            // get task
            $response = $this->sendRequest('tasks/getTask', ['id' => $taskId]);
            if ($response['result'] != 'success') {
                $this->console->output('Wrong response tasks/getTask');
                $this->console->output(var_export($response, true));
                return ExitCode::OK;
            }
            unset($response['result']);
            $task = $response['task'];

            // check poster
            if ($task['poster'] == null) {
                $this->console->output('Poster not set skip task');
                continue;
            }

            // get anchors
            $response = $this->sendRequest('getAnkors', ['task_id' => $taskId]);
            if ($response['result'] != 'success') {
                $this->console->output('Wrong response getAnkors');
                $this->console->output(var_export($response, true));
                return ExitCode::OK;
            }
            unset($response['result']);
            $anchors = $response;

            // create article and article anchors
            $this->console->output('Create article "' . $title . '"');
            $createResult = $this->createArticle($task, $anchors, null, null);
            if ($createResult['status'] == false) {
                $this->console->output(var_export($createResult, true));
            }
        }
        $this->console->endProgress();

        $this->stopCommand();
        return ExitCode::OK;
    }

    /**
     * Ping bytext
     */
    public function actionBytextPing()
    {
        $resp = $this->sendRequest('help');
        var_dump($resp);
    }

    /**
     * @param $action
     * @param null|array $queryParams
     * @return mixed
     */
    private function sendRequest($action, $queryParams = null)
    {
        $curl = curl_init();

        $url = 'http://bytext.ru/api.php?action=' . $action;
        if ($queryParams) {
            $queryParams['site'] = UrlHelper::getDomain(); //'sovets.net';
            $url .= '&' . http_build_query($queryParams);
        }

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

    /**
     * @param $str
     * @return string
     */
    private function mb_ucfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

    /**
     * @param $task
     * @param $anchors
     * @param $userId
     * @param $byTextApi
     * @return mixed
     */
    private function createArticle($task, $anchors, $userId, $byTextApi)
    {
        $firstAnkor = $anchors['ankors'][0];

        $content = preg_replace('/<h1>[^<]+<\/h1>/im', '', $task['text'], 1);
        $content = preg_replace('/<span class="marker note">((?:.*?\r?\n?)*)<\/span>/ui', '<div class="note">$1</div>', $content);

        $saveData = [
            'category_id' => null,
            'admin_id' => null,
            'title' => $this->mb_ucfirst($task['title']),
            'description' => $task['description'],
            'breadcrumbs' => $this->mb_ucfirst($firstAnkor),
            'content' => $content,
            'image_extension' => null,
            'created_at' => time(),
            'updated_at' => time(),
            'is_published' => 0,
            'published_at' => null,
            'is_ready_for_publish' => 0,
            'ready_publish_date' => null,
            'imported_at' => time(),
            'anounce_end_date' => null,
            'yandex_origin_date' => null,
            'checked_anounce_end' => 0,
            'show_banners' => 0,
            'visit_counter' => null,
            'publisher_id' => null,
            'expert_id' => null,
            'bytextId' => $task['id'],
            'visits_last_day' => '',
            'noindex' => 0,
            'main_query' => $this->mb_ucfirst($task['title']),
            'is_turbopage' => 0,
            'dzen' => (int)$task['zen'],
            'unique_users_yesterday_count' => null,
            'publisher_id' => null,
            'image' => null,
            'banner_group_id' => null,
        ];

        // find poster
        if (isset($task['poster'])) {

            $posterEmail = $task['poster']['email'];
            $poster = Admin::findByEmail($posterEmail);
            if ($poster) {
                $saveData['admin_id'] = $poster->id;
                $this->console->output('Found Poster ' . $poster->id . ' "' . $poster->email . '" for taskId ' . $task['id']);
            } else {
                $this->console->output('Not found poster email ' . $posterEmail . '"');
            }
        }

        $status = true;

        $article = new Article();
        $article->attributes = $saveData;
        if (!$this->readOnly) {
            $articleSave = $article->save();
            if (!$articleSave) {
                $status = false;
                $result['article'] = $article->getErrors();
            }
        }

        if (!$this->readOnly) {
            $articleMeta = new ArticleMeta();
            $articleMeta->article_id = $article->id;
            $articleMeta->meta_description = $task['description'];
            $s = $articleMeta->save();
            $this->console->output('Article meta save is ' . (boolean)$s);
            if (!$s) {
                $this->console->output(var_export($articleMeta->getErrors(), true));
            }
        }



        foreach ($anchors['ankorsTaskCreatorWordstat'] as $anchorTitle => $wordstatCount) {
            $anchor = new ArticleAnchor();
            $anchor->title = $anchorTitle;
            $anchor->article_id = $article->id;
            $anchor->wordstat_count = $wordstatCount;

            if (!$this->readOnly) {
                $anchorSave = $anchor->save();
                if (!$anchorSave) {
                    $status = false;
                    $result['anchor'][] = $anchor->getErrors();
                }
            }
        }

        if (!$this->readOnly) {
            // Send confirm that article received
            $this->sendRequest('tasks/setExportedToContentSite', ['id' => $task['id']]);
        }

        $result['status'] = $status;
        return $result;
    }
}
