<?php

use yii\db\Migration;

/**
 * Class m190707_044620_remove_deleted_from_articles
 */
class m190707_044620_remove_deleted_from_articles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('articles', 'is_deleted');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190707_044620_remove_deleted_from_articles cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_044620_remove_deleted_from_articles cannot be reverted.\n";

        return false;
    }
    */
}
