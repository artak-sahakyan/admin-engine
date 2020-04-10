<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articles`.
 */
class m181220_063135_create_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%articles}}', [
            'id'                =>  $this->primaryKey(),
            'category_id'       =>  $this->integer(),
            'category_child_id' =>  $this->integer(),
            'admin_id'         =>  $this->integer(),
            'title'             =>  $this->string()->notNull(),
            'slug'              =>  $this->string(),
            'description'       =>  $this->text(),
            'breadcrumbs'       =>  $this->string(),
            'meta_keywords'     =>  $this->text(),
            'meta_description'  =>  $this->text(),
            'meta_title'        =>  $this->string(),
            'content'           =>  $this->text(),
            'preview_image'     =>  $this->string(),
            'created_at'        =>  $this->integer(),
            'updated_at'        =>  $this->integer(),
            'is_published' => $this->boolean()->defaultValue(1),
            'published_at' => $this->integer(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');


        $this->addForeignKey('article_admin', '{{%articles}}', 'admin_id', '{{%admins}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('article_category', '{{%articles}}', 'category_id', '{{%article_categories}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('article_slug', '{{%articles}}', 'slug');
        $this->createIndex('article_is_published', '{{%articles}}', ['is_published', 'published_at']);


    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%articles}}');
    }
}
