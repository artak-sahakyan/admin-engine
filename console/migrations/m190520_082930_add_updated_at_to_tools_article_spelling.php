<?php

use yii\db\Migration;

/**
 * Class m190520_082930_add_updated_at_to_tools_article_spelling
 */
class m190520_082930_add_updated_at_to_tools_article_spelling extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%tools_article_spelling}}', 'updated_at',  $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%tools_article_spelling}}', 'updated_at');
    }


}
