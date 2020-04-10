<?php

use yii\db\Migration;

/**
 * Class m191002_071842_add_head_text_field_to_articles
 */
class m191002_071842_add_head_text_field_to_articles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles%}}', 'head_text', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('{{%articles%}}', 'head_text');
    }

}
