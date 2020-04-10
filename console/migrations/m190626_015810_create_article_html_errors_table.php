<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_html_errors}}`.
 */
class m190626_015810_create_article_html_errors_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%article_html_errors}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'content' => $this->text()
        ], $tableOptions);

        $this->createIndex('article_html_error_info_index', '{{%article_html_errors}}', 'article_id');
        $this->addForeignKey('article_html_error_info_fk','{{%article_html_errors}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_html_errors}}');
    }
}
