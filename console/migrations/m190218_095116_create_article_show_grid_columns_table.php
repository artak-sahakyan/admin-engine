<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_show_grid_columns}}`.
 */
class m190218_095116_create_article_show_grid_columns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%show_grid_columns}}', [
            'id' => $this->primaryKey(),
            'grid_id'   => $this->integer()->notNull(),
            'attribute' => $this->string(),
            'is_checked' => $this->boolean()->defaultValue(0),
        ]);

        $this->createIndex('show_grid_columns_index', '{{%show_grid_columns}}', 'grid_id');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%show_grid_columns}}');
    }
}
