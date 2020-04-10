<?php

use yii\db\Migration;

/**
 * Class m190123_053850_add_fields_to_article_table
 */
class m190123_053850_add_fields_to_article_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%articles}}', 'bytextId', $this->integer());
        $this->addColumn('{{%articles}}', 'visits_last_day', $this->integer());
        $this->addColumn('{{%articles}}', 'noindex', $this->tinyInteger());
        $this->addColumn('{{%articles}}', 'main_query', $this->string());
        $this->addColumn('{{%articles}}', 'is_turbopage', $this->tinyInteger());

    }

    public function down()
    {
        echo "m190123_053850_add_fields_to_article_table cannot be reverted.\n";

        return false;
    }

}
