<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_tags}}`.
 */
class m190124_054418_create_article_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_tags}}', [
            'id'         => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'title'     =>  $this->string()->notNull()
        ]);

        $this->addForeignKey('article_tags_articles','{{%article_tags}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_tags}}');
    }
}
