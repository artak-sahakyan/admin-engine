<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_rating}}`.
 */
class m190409_113437_create_article_rating_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_rating}}', [
            'id'            => $this->primaryKey(),
            'article_id'    => $this->integer()->notNull()->unique(),
            'positive'      => $this->integer()->notNull()->defaultValue(0),
            'negative'      => $this->integer()->notNull()->defaultValue(0),
            'comment'       => $this->text(),
        ]);

        $this->addForeignKey('article_rating_articles','{{%article_rating}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_rating}}');
    }
}
