<?php

use yii\db\Migration;

/**
 * Class m190205_162718_add_ips_to_admins_table
 */
class m190205_162718_add_ips_to_admins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%admins}}', 'ips', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190205_162718_add_ips_to_admins_table cannot be reverted.\n";

        return false;
    }

}
