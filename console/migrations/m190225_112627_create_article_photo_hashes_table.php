<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_photo_hashes}}`.
 */
class m190225_112627_create_article_photo_hashes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_photo_hashes}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'hash' => $this->string()->notNull()
        ]);

        $this->addForeignKey('articles_photo_hashes','{{%article_photo_hashes}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_photo_hashes}}');
    }
}
