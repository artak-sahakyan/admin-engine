<?php
namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use common\models\{ Voting, BannerGroup, ArticleCategory, Article };

class GetVotingsBehavior extends Behavior
{
    private static $id;
    private static $bannerGroup;
    private static $articleCategory;
    private static $article;

    /**
     * @param null|BannerGroup $bannerGroup
     * @return Voting
     */
    public function getOneVoting(int $id)
    {
        self::$id = $id;

        return self::getQuery()->one();
    }

    /**
     * @param null|BannerGroup $bannerGroup
     * @param null|array $places
     * @return array
     */
    public static function getAllVotings(BannerGroup $bannerGroup = null, ArticleCategory $articleCategory = null, Article $article = null)
    {
        self::$bannerGroup = $bannerGroup;
        self::$articleCategory = $articleCategory;
        self::$article = $article;

        return self::getQuery()->all();
    }

    /**
     * @return ActiveRecord
     */
    private function getQuery()
    {
        $voting = Voting::find();

        if(self::$id) {
            $voting->where(['id' => self::$id]);
        } else {
            if(self::$bannerGroup) {
                $voting->joinWith('bannerGroups')
                    ->andWhere([
                        'or',
                        [BannerGroup::tableName() . '.id' => self::$bannerGroup],
                        [BannerGroup::tableName() . '.id' => null]
                    ]);
            } else {
                $voting->joinWith('bannerGroups')->andWhere([BannerGroup::tableName() . '.id' => null]);
            }

            if(self::$articleCategory) {
                $voting->joinWith('articleCategories')
                    ->andWhere([
                        'or',
                        [ArticleCategory::tableName() . '.id' => self::$articleCategory->id],
                        [ArticleCategory::tableName() . '.id' => null]
                    ]);
            }

            if(self::$article) {
                $voting->joinWith('articles')
                    ->orWhere([Article::tableName() . '.id' => self::$article->id]);
            }
                    
        }

        return $voting;
    }
}
