<?php

use yii\db\Migration;

/**
 * Class m181220_112714_add_category_sort_field
 */
class m181220_112714_add_category_sort_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article_categories}}', 'sort', $this->integer());
        $this->createIndex('article_category_sort', '{{%article_categories}}', 'sort');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181220_112714_add_category_sort_field cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181220_112714_add_category_sort_field cannot be reverted.\n";

        return false;
    }
    */
}
