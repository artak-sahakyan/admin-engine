<?php

use yii\db\Migration;

/**
 * Class m190508_025521_add_column_image_template_rss
 */
class m190508_025521_add_column_image_template_rss extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%rss_channels}}', 'image_template',  $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190508_025521_add_column_image_template_rss cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190508_025521_add_column_image_template_rss cannot be reverted.\n";

        return false;
    }
    */
}
