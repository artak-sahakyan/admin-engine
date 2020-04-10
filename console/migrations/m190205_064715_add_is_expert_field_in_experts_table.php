<?php

use yii\db\Migration;

/**
 * Class m190205_064715_add_is_expert_field_in_experts_table
 */
class m190205_064715_add_is_expert_field_in_experts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%experts}}', 'is_expert', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190205_064715_add_is_expert_field_in_experts_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190205_064715_add_is_expert_field_in_experts_table cannot be reverted.\n";

        return false;
    }
    */
}
