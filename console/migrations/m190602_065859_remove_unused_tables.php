<?php

use yii\db\Migration;

/**
 * Class m190602_065859_remove_unused_tables
 */
class m190602_065859_remove_unused_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");
         $this->dropForeignKey('show_grid_columns_fk', 'show_grid_columns');
        
        $this->dropTable('{{%grid_views}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190602_065859_remove_unused_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190602_065859_remove_unused_tables cannot be reverted.\n";

        return false;
    }
    */
}
