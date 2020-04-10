<?php

use yii\db\Migration;

/**
 * Class m190716_131802_admin_vs_groups_insert
 */
class m190716_131802_admin_vs_groups_insert extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add Admin to administator group
        $this->db->createCommand("
            INSERT INTO `admin_vs_groups` (`admin_id`, `group_id`) VALUES (1, 1)
        ")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand("
            DELETE FROM `admin_vs_groups` WHERE `admin_id` = 1 AND `group_id` = 1
        ")->execute();
    }
}
