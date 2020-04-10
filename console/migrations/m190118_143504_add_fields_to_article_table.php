<?php

use yii\db\Migration;

/**
 * Class m190118_143504_add_fields_to_article_table
 */
class m190118_143504_add_fields_to_article_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%articles}}', 'show_banners', $this->tinyInteger(1));
        $this->addColumn('{{%articles}}', 'visit_counter', $this->integer());
        $this->addColumn('{{%articles}}', 'expert_id', $this->integer());
        $this->addColumn('{{%articles}}', 'publisher_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%articles}}', 'show_banners');
        $this->dropColumn('{{%articles}}', 'visit_counter');
        $this->dropColumn('{{%articles}}', 'expert_id');
        $this->dropColumn('{{%articles}}', 'publisher_id');
    }

}
