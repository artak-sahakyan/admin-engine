<?php

use yii\db\Migration;

/**
 * Class m190205_094551_add_fields_to_admin_table
 */
class m190205_094551_add_fields_to_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%admins}}', 'group_id', $this->integer(6));
        $this->addColumn('{{%admins}}', 'register_date', $this->integer());
        $this->addColumn('{{%admins}}', 'last_login_date', $this->integer());
        $this->addColumn('{{%admins}}', 'is_active', $this->tinyInteger());
        $this->addColumn('{{%admins}}', 'register_ip', $this->string(8));
        $this->addColumn('{{%admins}}', 'settings', $this->text());
        $this->addColumn('{{%admins}}', 'restrict_by_ip', $this->tinyInteger());

        $this->createIndex('admins-group_id',  '{{%admins}}','group_id');
        $this->createIndex('admins_is_active', '{{%admins}}','is_active');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190205_094551_add_fields_to_admin_table cannot be reverted.\n";

        return false;
    }
}
