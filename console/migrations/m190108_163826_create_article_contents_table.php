<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_contents`.
 */
class m190108_163826_create_article_contents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_contents}}', [
            'id'         => $this->primaryKey(),
            'article_id' => $this->integer(),
            'text'       => $this->text(),
        ]);

        $this->createIndex('article-contents-articlesId', '{{%article_contents}}', ['article_id']);
        $this->addForeignKey('article-contents-articles', '{{article_contents}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_contents}}');
    }
}
