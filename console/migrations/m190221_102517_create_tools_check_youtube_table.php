<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tools_check_youtube}}`.
 */
class m190221_102517_create_tools_check_youtube_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tools_youtube_missed}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(1),
            'link' => $this->string()->notNull(),
            'created_at'    => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tools_youtube_missed}}');
    }
}
