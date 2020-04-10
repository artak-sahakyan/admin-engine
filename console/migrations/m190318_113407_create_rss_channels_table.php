<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rss_channels}}`.
 */
class m190318_113407_create_rss_channels_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rss_channels}}', [
            'id'        => $this->primaryKey(),
            'title'     => $this->string(250),
            'alias'     => $this->string(50)->notNull()->unique(),
            'container_template'    => $this->text()->notNull(),
            'item_template'         => $this->text()->notNull(),
            'limit'                 => $this->integer()->defaultValue(100),
            'filter'                => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rss_channels}}');
    }
}
