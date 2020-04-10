<?php

use yii\db\Migration;

/**
 * Class m190419_143714_add_indexses_in_tables
 */
class m190419_143714_add_indexses_in_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('articles_banner_group_id', '{{%articles%}}', 'banner_group_id');
        $this->createIndex('articles_is_turbopage', '{{%articles%}}', 'is_turbopage');
        $this->createIndex('articles_bytext_id', '{{%articles%}}', 'bytextId');
        $this->createIndex('articles_publisher_id', '{{%articles%}}', 'publisher_id');
        $this->createIndex('articles_expert_id', '{{%articles%}}', 'expert_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('articles_banner_group_id');
        $this->dropIndex('articles_is_turbopage');
        $this->dropIndex('articles_bytext_id');
        $this->dropIndex('articles_publisher_id');
        $this->dropIndex('articles_expert_id');
    }

}
