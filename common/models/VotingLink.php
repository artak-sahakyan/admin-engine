<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voting_vs_article".
 *
 * @property int $id
 * @property int $voting_id
 * @property int $link_id
 *
 * @property Article $article
 * @property Voting $voting
 */
class VotingLink extends \yii\db\ActiveRecord
{
    const CATEGORY = 'category';
    const ARTICLE = 'article';
    const BANNERGROUP = 'bannerGroup';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%voting_links}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voting_id', 'link_id', 'morph'], 'required'],
            [['voting_id', 'link_id'], 'integer'],
            [['voting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::class, 'targetAttribute' => ['voting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voting_id' => 'Опрос',
            'link_id' => 'Связь',
            'morph' => 'Тип связи',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'link_id'])->andOnCondition(['morph' => self::ARTICLE]);
    }

    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::class, ['id' => 'link_id'])->andOnCondition(['morph' => self::CATEGORY]);
    }

    public function getBannerGroup()
    {
        return $this->hasOne(BannerGroup::class, ['id' => 'link_id'])->andOnCondition(['morph' => self::BANNERGROUP]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoting()
    {
        return $this->hasOne(Voting::class, ['id' => 'voting_id']);
    }
}
