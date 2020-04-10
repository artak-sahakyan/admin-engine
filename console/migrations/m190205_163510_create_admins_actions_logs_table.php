<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admins_actions_logs}}`.
 */
class m190205_163510_create_admins_actions_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admins_actions_logs}}', [
            'id'        => $this->primaryKey(),
            'datetime'  => $this->integer(),
            'ip'        => $this->string(15),
            'user_id'   => $this->integer(),
            'page'      => $this->string(),

        ]);

        $this->addForeignKey('admins_actions_logs_user_id', '{{%admins_actions_logs}}', 'user_id', '{{%admins}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admins_actions_logs}}');
    }
}
