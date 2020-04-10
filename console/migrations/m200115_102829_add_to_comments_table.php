<?php

use yii\db\Migration;

/**
 * Class m200115_102829_add_to_comments_table
 */
class m200115_102829_add_to_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%comments%}}', 'article_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200115_102829_add_to_comments_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200115_102829_add_to_comments_table cannot be reverted.\n";

        return false;
    }
    */
}
