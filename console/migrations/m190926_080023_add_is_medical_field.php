<?php

use yii\db\Migration;

/**
 * Class m190926_080023_add_is_medical_field
 */
class m190926_080023_add_is_medical_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article_categories%}}', 'is_medical', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190926_080023_add_is_medical_field cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190926_080023_add_is_medical_field cannot be reverted.\n";

        return false;
    }
    */
}
