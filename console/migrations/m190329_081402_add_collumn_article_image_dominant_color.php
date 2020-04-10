<?php

use yii\db\Migration;

/**
 * Class m190329_081402_add_collumn_article_image_dominant_color
 */
class m190329_081402_add_collumn_article_image_dominant_color extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'image_dominant_color', 'varchar(8) not null default "" AFTER image_extension');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%articles}}', 'image_dominant_color');
    }
}
