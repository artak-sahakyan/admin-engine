<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles_anchor}}`.
 */
class m190129_132104_create_article_anchors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_anchors}}', [
            'id'                => $this->primaryKey(),
            'article_id'        => $this->integer()->notNull(),
            'title'             => $this->string(255),
            'wordstat_count'    => $this->integer()
        ]);

        $this->addForeignKey('articles_anchor_articles','{{%article_anchors}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_anchors}}');
    }
}
