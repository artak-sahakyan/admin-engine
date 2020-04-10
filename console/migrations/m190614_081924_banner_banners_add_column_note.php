<?php

use yii\db\Migration;

/**
 * Class m190614_081924_banner_banners_add_column_note
 */
class m190614_081924_banner_banners_add_column_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('banner_banners', 'note', "text NOT NULL AFTER `content`");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('banner_banners', 'note');

    }
}
