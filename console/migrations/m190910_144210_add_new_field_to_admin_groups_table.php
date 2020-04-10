<?php

use yii\db\Migration;

/**
 * Class m190910_144210_add_new_field_to_admin_groups_table
 */
class m190910_144210_add_new_field_to_admin_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->addColumn('admins_groups', 'allow_change_publisher_and_poster', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        echo "m190910_144210_add_new_field_to_admin_groups_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190910_144210_add_new_field_to_admin_groups_table cannot be reverted.\n";

        return false;
    }
    */
}
