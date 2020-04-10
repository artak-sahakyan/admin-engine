<?php

use yii\db\Migration;
use \common\models\Admin;

/**
 * Class m190121_070011_add_rules_for_rbac
 */
class m190121_070011_add_rules_for_rbac extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $adminRole = $auth->createRole(Admin::ADMIN);
        $auth->add($adminRole);

        $seoRole = $auth->createRole(Admin::SEO);
        $auth->add($seoRole);

        $posterRole = $auth->createRole(Admin::POSTER);
        $auth->add($posterRole);

        $publisherRole = $auth->createRole(Admin::PUBLISHER);
        $auth->add($publisherRole);

        $correctorRole = $auth->createRole(Admin::CORRECTOR);
        $auth->add($correctorRole);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

}
