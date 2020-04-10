<?php

use yii\db\Migration;

/**
 * Class m190117_085146_add_fields_to_article_table
 */
class m190117_085146_add_fields_to_article_table extends Migration
{


    public function up()
    {
        $this->addColumn('{{%articles}}', 'is_ready_for_publish', $this->tinyInteger(1)->defaultValue(0));
        $this->addColumn('{{%articles}}', 'checked_anounce_end', $this->tinyInteger(1)->defaultValue(0));
        $this->addColumn('{{%articles}}', 'ready_publish_date', $this->integer());
        $this->addColumn('{{%articles}}', 'imported_at', $this->integer());
        $this->addColumn('{{%articles}}', 'anounce_end_date', $this->integer());
        $this->addColumn('{{%articles}}', 'yandex_origin_date', $this->integer());
    }

    public function down()
    {
        return true;
    }

}
