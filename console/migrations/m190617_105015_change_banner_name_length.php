<?php

use yii\db\Migration;

/**
 * Class m190617_105015_change_banner_name_length
 */
class m190617_105015_change_banner_name_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('banner_banners', 'name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190617_105015_change_banner_name_length cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190617_105015_change_banner_name_length cannot be reverted.\n";

        return false;
    }
    */
}
