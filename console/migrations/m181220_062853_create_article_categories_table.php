<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_categories`.
 */
class m181220_062853_create_article_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%article_categories}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'slug' => $this->string(),
            'title' => $this->string()->notNull(),
            'h1Title' => $this->string(),
            'metaTitle' => $this->string(),
            'metaDescription' => $this->string(),
            'metaKeywords' => $this->string(),
            'text' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');


        $this->createIndex('category_parent', '{{%article_categories}}', 'parent_id');
        $this->createIndex('slug', '{{%article_categories}}', 'slug');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%article_categories}}');
    }
}
