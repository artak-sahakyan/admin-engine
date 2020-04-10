<?php

use yii\db\Migration;

/**
 * Class m200327_133049_add_sidebar_fix
 */
class m200327_133049_add_sidebar_fix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles%}}', 'is_fix_sidebar', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200327_133049_add_sidebar_fix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200327_133049_add_sidebar_fix cannot be reverted.\n";

        return false;
    }
    */
}
