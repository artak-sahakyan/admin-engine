<?php

use yii\db\Migration;

/**
 * Class m190603_071749_add_column_is_double_banner_place_manual_fix_to_articles
 */
class m190603_071749_add_column_is_double_banner_place_manual_fix_to_articles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $comment = 'Ручная правка дублирования баннеров';
        $this->addColumn(
            '{{%articles}}',
            'is_double_banner_place_manual_fix',
            $this->tinyInteger(1)->unsigned()->notNull()->comment($comment)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{$articles}}', 'is_double_banner_place_manual_fix');
    }
}
