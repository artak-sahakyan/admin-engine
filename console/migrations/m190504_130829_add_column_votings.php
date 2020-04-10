<?php

use yii\db\Migration;

/**
 * Class m190504_130829_add_column_votings
 */
class m190504_130829_add_column_votings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%votings}}', 'show_article',  $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190504_130829_add_column_votings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190504_130829_add_column_votings cannot be reverted.\n";

        return false;
    }
    */
}
