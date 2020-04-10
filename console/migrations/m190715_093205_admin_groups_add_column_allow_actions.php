<?php

use yii\db\Migration;

/**
 * Class m190715_093205_admin_groups_add_column_allow_actions
 */
class m190715_093205_admin_groups_add_column_allow_actions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('admins_groups', 'allow_actions', 'JSON NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('admins_groups', 'allow_actions');
    }
}
