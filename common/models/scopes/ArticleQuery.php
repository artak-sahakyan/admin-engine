<?php

namespace common\models\scopes;

use yii\db\ActiveQuery;

class ArticleQuery extends ActiveQuery
{
    public function published()
    {
        return $this->andWhere(['AND',['is_published' => 1], ['<', 'published_at', (time() + 60 * 60 * 3)]]);
    }

    public function unpublished()
    {
        return $this->andWhere(['OR',['is_published' => 0], ['>', 'published_at', (time() + 60 * 60 * 3)]]);
    }
}