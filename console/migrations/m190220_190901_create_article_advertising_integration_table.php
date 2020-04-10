<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_advertising_integration}}`.
 */
class m190220_190901_create_article_advertising_integration_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%article_advertising_integrations}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'text' => $this->text(),
            'name' => $this->string(),
            'end_date' => $this->integer(),
            'is_active' => $this->boolean()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('article_advertising_integration_article_index', '{{%article_advertising_integrations}}', 'article_id');
        $this->addForeignKey('article_advertising_integration_article_fk', '{{%article_advertising_integrations}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%article_advertising_integrations}}');
    }
}
