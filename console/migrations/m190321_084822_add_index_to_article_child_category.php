<?php

use yii\db\Migration;

/**
 * Class m190321_084822_add_index_to_article_child_category
 */
class m190321_084822_add_index_to_article_child_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('articles_category_child_id', '{{%articles}}', 'category_child_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('articles_category_child_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190321_084822_add_index_to_article_child_category cannot be reverted.\n";

        return false;
    }
    */
}
