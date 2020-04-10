<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles_metas}}`.
 */
class m190123_070822_create_article_metas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropColumn('{{%articles}}', 'meta_keywords');
        $this->dropColumn('{{%articles}}', 'meta_description');
        $this->dropColumn('{{%articles}}', 'meta_title');

        $this->createTable('{{%article_metas}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_metas}}');
    }
}
