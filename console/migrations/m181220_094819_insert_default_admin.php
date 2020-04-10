<?php

use yii\db\Migration;
use common\models\Admin;

/**
 * Class m181220_094819_insert_default_admin
 */
class m181220_094819_insert_default_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $admin = new Admin;
        $admin->username = 'admin';
        $admin->email = 'sovets@mail.ru';
        $admin->setPassword('sovets2018');
        $admin->generateAuthKey();
        $admin->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181220_094819_insert_default_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181220_094819_insert_default_admin cannot be reverted.\n";

        return false;
    }
    */
}
