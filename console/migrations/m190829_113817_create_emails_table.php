<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%emails}}`.
 */
class m190829_113817_create_emails_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('email', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string(),
            'content' => $this->string(),
            'sent' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        return false;
    }
}
