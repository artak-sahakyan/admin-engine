<?php

use yii\db\Migration;

/**
 * Class m190122_072704_add_employee_data
 */
class m190122_072704_add_employee_data extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
        $publisherRole = $auth->getRole(\common\models\Admin::PUBLISHER);
        $posterRole = $auth->getRole(\common\models\Admin::POSTER);

        for ($i = 1; $i<5; $i++) {
            $admin = new \common\models\Expert();
            $admin->username = "expert$i";
            $admin->email = "expert$i@mail.ru";
            $admin->setPassword('sovets2018');
            $admin->generateAuthKey();
            $admin->save(false);

        }

        for ($i = 1; $i<3; $i++) {
            $admin = new \common\models\Admin();
            $admin->username = "publisher$i";
            $admin->email = "publisher$i@mail.ru";
            $admin->setPassword('sovets2018');
            $admin->generateAuthKey();
            $admin->save(false);

            $auth->assign($publisherRole, $admin->id);
        }

        for ($i = 1; $i<3; $i++) {
            $admin = new \common\models\Admin();
            $admin->username = "poster$i";
            $admin->email = "poster$i@mail.ru";
            $admin->setPassword('sovets2018');
            $admin->generateAuthKey();
            $admin->save(false);

            $auth->assign($posterRole, $admin->id);
        }
    }

    public function down()
    {
        echo "m190122_072704_add_employee_data cannot be reverted.\n";

        return false;
    }

}
