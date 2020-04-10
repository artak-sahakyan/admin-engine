<?php

use yii\db\Migration;

/**
 * Class m191002_110038_add_head_text_field_to_category_table
 */
class m191002_110038_add_head_text_field_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article_categories%}}', 'head_text', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%article_categories%}}', 'head_text');
    }
}
