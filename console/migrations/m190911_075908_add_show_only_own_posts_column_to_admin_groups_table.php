<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%admin_groups}}`.
 */
class m190911_075908_add_show_only_own_posts_column_to_admin_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->addColumn('admins_groups', 'show_only_own_posts', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
    }
}
