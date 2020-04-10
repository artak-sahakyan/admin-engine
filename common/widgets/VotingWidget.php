<?php
namespace common\widgets;

use common\behaviors\GetVotingsBehavior;
use common\models\{ Voting, BannerGroup, ArticleCategory, Article };
use yii\base\Widget;

/**
 * Виджет, выводящий  опрос по id
 */
class VotingWidget extends Widget
{
    use \common\traits\WidgetRenderPriorityTrait;

    /**
     * @var Id опроса
     */
    public $id;

    /**
     * @var позиция отображения
     */
    public $place;

    /**
     * @var Article
     */
    public $article;

    public function behaviors()
    {
        return [
            GetVotingsBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if($this->id) {
            $voting = GetVotingsBehavior::getOneVoting($this->id);
        } else {
            if (!empty($this->article) && !empty($this->place)) {
                $voting = $this->article->votings($this->place) ?: null;
            }

            // if votings not found these positions
            if (empty($voting)) {
                return '';
            }
        }



        return $this->render('voting', ['voting' => $voting, 'article' => $this->article]);
    }
}
