<?php

use yii\db\Migration;

/**
 * Class m190710_093533_banner_service_add_amp
 */
class m190710_093533_banner_service_add_amp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('banner_banners', 'service', "ENUM('dfp', 'realbig', 'amp') DEFAULT 'dfp' NOT NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('banner_banners', 'service', "ENUM('dfp', 'realbig') DEFAULT 'dfp' NOT NULL");
    }
}
