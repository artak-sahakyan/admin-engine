<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%grid_views}}`.
 */
class m190218_122656_create_grid_views_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%grid_views}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string()
        ]);

        $this->addForeignKey('show_grid_columns_fk', '{{%show_grid_columns}}', 'grid_id', '{{%grid_views}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%grid_views}}');
    }
}
