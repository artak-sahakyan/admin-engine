<?php

use yii\db\Migration;

/**
 * Class m190621_101239_add_constraint_tools_youtube_missed_table
 */
class m190621_101239_add_constraint_tools_youtube_missed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE `tools_youtube_missed` ADD INDEX( `article_id`)')->execute();
        Yii::$app->db->createCommand('ALTER TABLE `tools_youtube_missed` ADD CONSTRAINT `tools_youtube_missed_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE ON UPDATE CASCADE')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('ALTER TABLE `tools_youtube_missed` DROP FOREIGN KEY `tools_youtube_missed_article_id`')->execute();
        Yii::$app->db->createCommand('ALTER TABLE `tools_youtube_missed` ADD INDEX( `article_id`)')->execute();
    }
}
