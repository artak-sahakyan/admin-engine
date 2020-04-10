<?php

use yii\db\Migration;

/**
 * Class m190611_115018_banner_banners_add_column_service
 */
class m190611_115018_banner_banners_add_column_service extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('banner_banners', 'service', "ENUM('dfp', 'realbig') DEFAULT 'dfp' NOT NULL AFTER `content`");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('banner_banners', 'service');
    }
}
