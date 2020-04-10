<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%experts_categories}}`.
 */
class m190202_184736_create_experts_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%experts_categories}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('experts-categories-userId', '{{%experts_categories}}', 'user_id');
        $this->createIndex('experts-categories-categoryId', '{{%experts_categories}}', 'category_id');

        $this->addForeignKey('experts-categories-experts', '{{%experts_categories}}', 'user_id', '{{%experts}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%experts_categories}}');
    }
}
