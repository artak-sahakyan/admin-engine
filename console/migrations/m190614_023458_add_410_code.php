<?php

use yii\db\Migration;

/**
 * Class m190614_023458_add_410_code
 */
class m190614_023458_add_410_code extends Migration
{
    public function safeUp()
    {
        $comment = 'Отдавать 410 ответ';
        $this->addColumn(
            '{{%articles}}',
            'is_deleted',
            $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0)->comment($comment)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{$articles}}', 'is_deleted');
    }
}
