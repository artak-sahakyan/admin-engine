<?php

namespace common\models;

use common\behaviors\UpdateLinksBehavior;
use Yii;

/**
 * This is the model class for table "voting".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $show_sidebar
 * @property int $show_bottom
 * @property int $show_main

 *
 * @property VotingAnswers[] $votingAnswers
 * @property array $votingArticles
 * @property VotingVsArticleCategories[] $votingVsArticleCategories
 * @property VotingVsBannerGroups[] $votingVsBannerGroups
 */
class Voting extends \yii\db\ActiveRecord
{
    public $votingArticles;

    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_ARTICLE = 'article';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%votings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['show_sidebar', 'show_bottom', 'show_main', 'show_article'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique'],
            ['votingArticles', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'title' => 'Заголовок',
            'show_sidebar' => 'Отображать в сайдбаре',
            'show_bottom' => 'Отображать внизу статьи',
            'show_main' => 'Отображать на главной',
            'bannerGroups' => 'Баннерные группы',
            'articleCategories' => 'Категории статей',
            'show_article' => 'Отображать в статье',
            'answers' => 'Варианты ответов',
            'articles' => 'Статьи'
        ];
    }

    public function behaviors()
    {
        return [
            UpdateLinksBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(VotingAnswer::class, ['voting_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingVsArticleCategories()
    {
        return $this->hasMany(VotingLink::class, ['voting_id' => 'id'])->alias('VotingVsArticleCategories')->andOnCondition(['VotingVsArticleCategories.morph' => VotingLink::CATEGORY]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingVsArticle()
    {
        return $this->hasMany(VotingLink::class, ['voting_id' => 'id'])->alias('VotingVsArticle')->andOnCondition(['VotingVsArticle.morph' => VotingLink::ARTICLE]);
    }

    public function getArticles() {
        return $this->hasMany(Article::class, ['id' => 'link_id'])
          ->via('votingVsArticle')->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingVsBannerGroups()
    {
        return $this->hasMany(VotingLink::class, ['voting_id' => 'id'])->alias('VotingVsBannerGroups')->andOnCondition(['VotingVsBannerGroups.morph' => VotingLink::BANNERGROUP]);
    }

    public function getBannerGroups() {
        return $this->hasMany(BannerGroup::class, ['id' => 'link_id'])
          ->via('votingVsBannerGroups')->indexBy('id');
    }

    public function getArticleCategories() {
        return $this->hasMany(ArticleCategory::class, ['id' => 'link_id'])
          ->via('votingVsArticleCategories')->indexBy('id');
    }
}
