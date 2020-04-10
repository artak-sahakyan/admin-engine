<?php

use yii\db\Migration;

/**
 * Class m190531_121353_common_data
 */
class m190531_121353_common_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%common_data}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(256)->notNull(),
            'value' => $this->json()->notNull(),
            'created_at' => $this->integer(10)->notNull()->unsigned(),
            'updated_at' => $this->integer(10)->notNull()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%common_data}}');
    }

}
