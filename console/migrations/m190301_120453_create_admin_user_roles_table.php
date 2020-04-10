<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user_roles}}`.
 */
class m190301_120453_create_admin_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_vs_groups}}', [
            'id' => $this->primaryKey(),
            'admin_id'  => $this->integer()->notNull(),
            'group_id'   => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-role-role','{{%admin_vs_groups}}','group_id','{{%admins_groups}}','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_vs_groups}}');
    }
}
