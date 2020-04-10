<?php

use yii\db\Migration;

/**
 * Class m190116_051224_change_article_table_preview_image_to_image_extenshion
 */
class m190116_051224_change_article_table_preview_image_to_image_extenshion extends Migration
{

    public function up()
    {
        $this->dropColumn('{{%articles}}', 'preview_image');
        $this->addColumn('{{%articles}}', 'image_extension', $this->string(10));
    }

    public function down()
    {
       return true;
    }

}
