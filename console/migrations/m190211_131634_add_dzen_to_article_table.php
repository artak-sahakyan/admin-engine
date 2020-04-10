<?php

use yii\db\Migration;

/**
 * Class m190211_131634_add_dzen_to_article_table
 */
class m190211_131634_add_dzen_to_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'dzen', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190211_131634_add_dzen_to_article_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190211_131634_add_dzen_to_article_table cannot be reverted.\n";

        return false;
    }
    */
}
