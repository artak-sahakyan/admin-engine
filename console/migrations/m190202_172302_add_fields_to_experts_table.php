<?php

use yii\db\Migration;

/**
 * Class m190202_172302_add_fields_to_experts_table
 */
class m190202_172302_add_fields_to_experts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%experts}}', 'registr_date', $this->integer());
        $this->addColumn('{{%experts}}', 'city', $this->string());
        $this->addColumn('{{%experts}}', 'birthdate', $this->integer());
        $this->addColumn('{{%experts}}', 'gender', $this->string(1));
        $this->addColumn('{{%experts}}', 'articles_count', $this->integer());
        $this->addColumn('{{%experts}}', 'think', $this->text());
        $this->addColumn('{{%experts}}', 'married', $this->boolean());

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190202_172302_add_fields_to_experts_table cannot be reverted.\n";

        return false;
    }

}
