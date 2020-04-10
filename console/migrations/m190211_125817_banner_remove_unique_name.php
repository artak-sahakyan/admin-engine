<?php

use yii\db\Migration;

/**
 * Class m190211_125817_banner_remove_unique_name
 */
class m190211_125817_banner_remove_unique_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%banner_banners}}', 'name');
        $this->addColumn('{{%banner_banners}}', 'name', $this->string(50)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190211_125817_banner_remove_unique_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190211_125817_banner_remove_unique_name cannot be reverted.\n";

        return false;
    }
    */
}
