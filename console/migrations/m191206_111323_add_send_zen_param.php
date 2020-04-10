<?php

use yii\db\Migration;

/**
 * Class m191206_111323_add_send_zen_param
 */
class m191206_111323_add_send_zen_param extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles%}}', 'send_zen', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191206_111323_add_send_zen_param cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191206_111323_add_send_zen_param cannot be reverted.\n";

        return false;
    }
    */
}
