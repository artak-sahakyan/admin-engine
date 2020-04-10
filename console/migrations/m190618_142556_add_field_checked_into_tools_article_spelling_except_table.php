<?php

use yii\db\Migration;

/**
 * Class m190618_142556_add_field_checked_into_tools_article_spelling_except_table
 */
class m190618_142556_add_field_checked_into_tools_article_spelling_except_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tools_article_spelling_except%}}', 'checked', $this->boolean()->defaultValue(0));

        \common\models\ArticleSpellingExcept::updateAll(['checked' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tools_article_spelling_except%}}', 'checked');
    }
}
