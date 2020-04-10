<?php

use yii\db\Migration;

/**
 * Class m190205_065937_change_experts_married_type_to_varchar
 */
class m190205_065937_change_experts_married_type_to_varchar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE `experts` CHANGE `married` `married` VARCHAR(25) NULL DEFAULT NULL;')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190205_065937_change_experts_married_type_to_varchar cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190205_065937_change_experts_married_type_to_varchar cannot be reverted.\n";

        return false;
    }
    */
}
