<?php

use yii\db\Migration;

/**
 * Class m190417_131254_update_banners_table
 */
class m190417_131254_update_banners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE `banner_banners` CHANGE `group_id` `group_id` INT(11) NULL DEFAULT 0')->execute();

        Yii::$app->db->createCommand('UPDATE banner_banners
        SET group_id = 0
        WHERE group_id IS NULL')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190417_131254_update_banners_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190417_131254_update_banners_table cannot be reverted.\n";

        return false;
    }
    */
}
