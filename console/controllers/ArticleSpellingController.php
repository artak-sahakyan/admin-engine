<?php
namespace console\controllers;

use console\helpers\QueryHelper;
use Yii;
use common\models\{
    Article, ArticleSpelling, ArticleSpellingExcept
};
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use Yandex\Speller\SpellerClient;

/**
 * Class ArticleSpellingController
 *
 *   php yii article-spelling/remove-by-word
 *
 * @package console\controllers
 */
class ArticleSpellingController extends ConsoleController
{

    protected $countArticles;
    protected $counterProgress = 1;

    /**
     * @param null $id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '64M');
        ini_set('max_execution_time', 0);

        $this->startCommand();
        $this->console->output('Проверка орфографии');

        $errors = self::processArticles();
      
        if($errors > 0) {
            $this->console->output('Найдено ' . $errors . ' статей с ошибками');
        } else {
            $this->console->output('Не найдено ошибок в статьях');
        }

        $this->stopCommand();
        return ExitCode::OK;
    }

    public function actionUpdateOne($word) {

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $this->startCommand();
        $this->removeByWord($word);
        $except = ArticleSpellingExcept::find()->where(['title' => $word])->one();
        $except->checked = 1;
        $except->save();

        $this->stopCommand();
        return ExitCode::OK;
    }

    public function actionRemoveByWord() {

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        $this->startCommand();

        $data = ArticleSpellingExcept::getAddedWords();
        $checkArticles = $data['words'];
        $this->countArticles = $data['total'];

        $this->console->output('Всего ' . $this->countArticles . ' статей для проверки');

        if($checkArticles) {
            foreach ($checkArticles as $k => $title) {
                $this->console->startProgress($this->counterProgress, $this->countArticles);
                $this->removeByWord($title);
                $except = ArticleSpellingExcept::findOne($k);
                $except->checked = 1;
                $except->save();
            }
        }

        $this->stopCommand();
        return ExitCode::OK;

    }

    private function removeByWord(string $word)
    {
        $word = mb_strtolower($word);
        $articleSpelling = ArticleSpelling::find()
            ->select(['id', 'title', 'content', 'article_id'])
            ->where(['OR LIKE', 'LOWER(content)', $word])
            ->orWhere(['OR LIKE', 'LOWER(title)', $word]);

        foreach ($articleSpelling->batch(100) as $spellings) {
            foreach ($spellings as $spell) {

                $spell->content = $this->filterWords($spell->content, $word);
                $spell->title = $this->filterWords($spell->title, $word);
                $spell->save();

            }

            Yii::$app->db->createCommand("DELETE FROM tools_article_spelling WHERE `content`='a:0:{}' AND title='a:0:{}'")->execute();
        }

        $this->console->endProgress();
        return true;
    }

    private function filterWords($words, $word)
    {
        $newContent = [];
        if ($content = unserialize($words)) {

            $newContent = array_filter($content, function ($var) use ($word) {
                return trim(mb_strtolower($var)) != trim(mb_strtolower($word));
            });
        }
        return serialize($newContent);
    }



    private function processArticles()
    {
        $query = Article::find()->orderBy('id');
        $countArticles = $query->count();

        $this->console->output('Всего ' . $countArticles . ' статей для проверки');

        $counterProgress = 1;
        $errors = 0;

        $this->console->startProgress($counterProgress, $countArticles);



        foreach (QueryHelper::getRows($query) as $article) {
            $this->console->output('Проверяем ' . $article->id);
            $response = ArticleSpelling::checkArticleAndSave($article);
            if ($response != null) {
                $errors++;
            };
            Console::updateProgress($counterProgress++, $countArticles);
        }

        $this->console->endProgress();

        return $errors;
    }
}
