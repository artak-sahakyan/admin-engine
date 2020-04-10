<?php

use yii\db\Migration;

/**
 * Class m190311_175621_change_counter_table_prefix
 */
class m190311_175621_change_counter_table_prefix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        Yii::$app->db->createCommand('RENAME TABLE `counters` TO `tools_counters`')->execute();
         Yii::$app->db->createCommand('DROP TABLE `metas`')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        return false;
    }
}
