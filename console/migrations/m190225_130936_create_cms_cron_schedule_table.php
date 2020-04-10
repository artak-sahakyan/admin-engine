<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cms_cron_schedule}}`.
 */
class m190225_130936_create_cms_cron_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%cms_cron_schedules}}', [
            'id'            => $this->primaryKey(),
            'command'       => $this->string()->notNull(),
            'schedule'      => $this->string(50)->notNull(),
            'is_active'     => $this->boolean(),
            'params'        => $this->string(),
            'description'   => $this->text()
        ], $tableOptions);


    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%cms_cron_schedules}}');
    }
}
