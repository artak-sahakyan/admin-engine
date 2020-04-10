<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_socials}}`.
 */
class m190407_031546_create_article_socials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_socials}}', [
            'id'            => $this->primaryKey(),
            'article_id'    => $this->integer(100)->notNull()->unique(),
            'sended_vk'     => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_socials}}');
    }
}
