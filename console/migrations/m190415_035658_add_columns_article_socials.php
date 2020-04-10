<?php

use yii\db\Migration;

/**
 * Class m190415_035658_add_columns_article_socials
 */
class m190415_035658_add_columns_article_socials extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article_socials}}', 'sended_ok', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190415_035658_add_columns_article_socials cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190415_035658_add_columns_article_socials cannot be reverted.\n";

        return false;
    }
    */
}
