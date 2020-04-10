<?php

use yii\db\Migration;

/**
 * Class m190303_045500_drop_column_group_id_article
 */
class m190303_045500_drop_column_group_id_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%admins}}', 'group_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190303_045500_drop_column_group_id_article cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190303_045500_drop_column_group_id_article cannot be reverted.\n";

        return false;
    }
    */
}
