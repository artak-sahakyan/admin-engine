<?php

use yii\db\Migration;

/**
 * Class m190610_034958_update_nausea_table
 */
class m190610_034958_update_nausea_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%nausea_of_articles}}', 'miratext_water', $this->float());
        $this->addColumn('{{%nausea_of_articles}}', 'miratext_bigram', $this->float());
        $this->addColumn('{{%nausea_of_articles}}', 'miratext_trigram', $this->float());
        $this->addColumn('{{%nausea_of_articles}}', 'miratext_words', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190610_034958_update_nausea_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190610_034958_update_nausea_table cannot be reverted.\n";

        return false;
    }
    */
}
