<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comments}}`.
 */
class m191228_045224_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text(),
            'ip' => $this->string(),
            'datеtime' => $this->string(),
            'rating' => $this->integer(),
            'attaches' => $this->json(),
            'visible' => $this->boolean()->defaultValue(1),
            'user_id' => $this->integer(),
            'nick' => $this->string(),
            'name' => $this->string(),
            'email' => $this->string(),
            'phone' => $this->string(),
            'avatar' => $this->string(),
            'chat_id' => $this->integer(),
            'url' => $this->string(),
            'title' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comments}}');
    }
}
