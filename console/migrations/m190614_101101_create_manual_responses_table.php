<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manual_responses}}`.
 */
class m190614_101101_create_manual_responses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%manual_responses}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'code' => $this->smallInteger()->defaultValue(410)->notNull(),
            'redirect_url' => $this->string()
        ], $tableOptions);

        $this->createIndex('manual_responses_urls', '{{%manual_responses}}', 'url');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%manual_responses}}');
    }
}
