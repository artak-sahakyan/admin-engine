<?php

use yii\db\Migration;

/**
 * Class m190624_110150_youtoobe_missed
 */
class m190624_110150_youtoobe_missed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('tools_youtube_missed', 'position', 'missed_position');
        $this->renameColumn('tools_youtube_missed', 'created_at', 'missed_updated_at');

        $this->alterColumn('tools_youtube_missed', 'missed_position', 'VARCHAR(256) NULL');

        $this->addColumn('tools_youtube_missed', 'title', 'VARCHAR(256) NOT NULL AFTER `article_id`');
        $this->addColumn('tools_youtube_missed', 'cover', 'VARCHAR(256) NOT NULL AFTER `title`');
        $this->addColumn('tools_youtube_missed', 'created_at', 'INT(10) UNSIGNED NOT NULL AFTER `link`');
        $this->addColumn('tools_youtube_missed', 'updated_at', 'INT(10) UNSIGNED NOT NULL AFTER `created_at`');

        $this->renameTable('tools_youtube_missed', 'article_youtube');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('article_youtube', 'tools_youtube_missed');

        $this->dropColumn('tools_youtube_missed', 'updated_at');
        $this->dropColumn('tools_youtube_missed', 'created_at');
        $this->dropColumn('tools_youtube_missed', 'cover');
        $this->dropColumn('tools_youtube_missed', 'title');

        $this->alterColumn('tools_youtube_missed', 'missed_position', 'VARCHAR(255) NOT NULL');

        $this->renameColumn('tools_youtube_missed', 'missed_updated_at', 'created_at');
        $this->renameColumn('tools_youtube_missed', 'missed_position', 'position');
    }
}
