<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_error_info}}`.
 */
class m190411_132209_create_article_error_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%article_error_info}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'error_in_text' => $this->text(),
            'date_send' => $this->integer()
        ], $tableOptions);

        $this->createIndex('article_error_info_index', '{{%article_error_info}}', 'article_id');
        $this->addForeignKey('article_error_info_fk','{{%article_error_info}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_error_info}}');
    }
}
