<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tools_article_spelling}}`.
 */
class m190227_020308_create_tools_article_spelling_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tools_article_spelling}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'content' => $this->text(),
            'title' => $this->string(),
        ]);

        $this->addForeignKey('articles_article_spelling','{{%tools_article_spelling}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tools_article_spelling}}');
    }
}
