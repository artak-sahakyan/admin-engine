<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tools_article_spelling_except}}`.
 */
class m190227_092526_create_tools_article_spelling_except_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tools_article_spelling_except}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tools_article_spelling_except}}');
    }
}
