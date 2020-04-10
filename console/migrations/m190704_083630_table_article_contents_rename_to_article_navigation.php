<?php

use yii\db\Migration;

/**
 * Class m190704_083630_table_article_contents_rename_to_article_navigation
 */
class m190704_083630_table_article_contents_rename_to_article_navigation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%article_contents}}', '{{%article_navigation}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable( '{{%article_navigation}}', '{{%article_contents}}');
    }
}
