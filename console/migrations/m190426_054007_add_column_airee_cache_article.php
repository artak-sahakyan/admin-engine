<?php

use yii\db\Migration;

/**
 * Class m190426_054007_add_column_airee_cache_article
 */
class m190426_054007_add_column_airee_cache_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'airee_clear_cache_date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_054007_add_column_airee_cache_article cannot be reverted.\n";

        return false;
    }
    */
}
