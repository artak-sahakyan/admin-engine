<?php

use yii\db\Migration;

/**
 * Class m190528_120615_add_column_is_actual_to_article
 */
class m190528_120615_add_column_is_actual_to_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'is_actual', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%articles}}', 'is_actual');
    }
}
