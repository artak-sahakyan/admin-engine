<?php

use yii\db\Migration;

/**
 * Class m190919_104957_remove_unused_tables
 */
class m190919_104957_remove_unused_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->execute("SET foreign_key_checks = 0;");
        
        // $this->dropTable('{{%admins_actions_logs}}');
        // $this->dropTable('{{%article_tags}}');
        // $this->dropTable('{{%auth_item_child}}');
        // $this->dropTable('{{%auth_rule}}');
        // $this->dropTable('{{%experts_categories}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190919_104957_remove_unused_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190919_104957_remove_unused_tables cannot be reverted.\n";

        return false;
    }
    */
}
