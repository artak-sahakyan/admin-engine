<?php

use yii\db\Migration;

/**
 * Class m190403_082531_add_column_banner_devices
 */
class m190403_082531_add_column_banner_devices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%banner_devices}}', 'alias', $this->string());

         Yii::$app->db->createCommand('UPDATE `banner_devices` SET `alias` = \'all\' WHERE `banner_devices`.`id` = 1 ;
                UPDATE `banner_devices` SET `alias` = \'desktop\' WHERE `banner_devices`.`id` = 2 ;
                UPDATE `banner_devices` SET `alias` = \'mobile\' WHERE `banner_devices`.`id` = 3 ;
                UPDATE `banner_devices` SET `alias` = \'tablet\' WHERE `banner_devices`.`id` = 4 ;' 
            )->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190403_082531_add_column_banner_devices cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190403_082531_add_column_banner_devices cannot be reverted.\n";

        return false;
    }
    */
}
