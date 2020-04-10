<?php

use yii\db\Migration;

/**
 * Class m190402_074207_modify_column_article_image_dominant_color
 */
class m190402_074207_modify_column_article_image_dominant_color extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%articles}}', 'image_dominant_color');
        $this->addColumn('{{%articles}}', 'image_color', 'JSON NOT NULL AFTER image_extension');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%articles}}', 'image_color');
        $this->addColumn('{{%articles}}', 'image_dominant_color', 'varchar(8) not null default "" AFTER image_extension');
    }
}
