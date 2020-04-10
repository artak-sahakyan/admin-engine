<?php

use yii\db\Migration;

/**
 * Class m190524_102356_cron_log
 */
class m190524_102356_cron_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cron_log', [
            'id' => $this->primaryKey(10)->unsigned(),
            'command' => $this->string(256)->notNull(),
            'status' => "ENUM('running', 'fail', 'done') NOT NULL",
            'progress' => $this->integer(10)->notNull()->unsigned(),
            'created_at' => $this->integer(10)->notNull()->unsigned(),
            'updated_at' => $this->integer(10)->notNull()->unsigned(),
        ]);

        $this->createTable('cron_log_message', [
            'id' => $this->primaryKey(10)->unsigned(),
            'cron_log_id' => $this->integer(10)->notNull()->unsigned(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer(10)->notNull()->unsigned(),
        ]);

        $this->createIndex('idx-cron_log_message-cron_log_id', 'cron_log_message', 'cron_log_id');

        $this->addForeignKey(
            'fk-cron_log_message-cron_log_id',
            'cron_log_message',
            'cron_log_id',
            'cron_log',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cron_log_message-cron_log_id', 'cron_log_message');

        $this->dropIndex('idx-cron_log_message-cron_log_id', 'cron_log_message');

        $this->dropTable('cron_log_message');

        $this->dropTable('cron_log');
    }
}
