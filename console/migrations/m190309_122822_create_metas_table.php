<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%metas}}`.
 */
class m190309_122822_create_metas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%metas}}', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string(),
            'keywords'      => $this->string(1024),
            'description'   => $this->string(1024)
        ], $tableOptions);

        $this->addData();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%metas}}');
    }

    public function addData() {
        return Yii::$app->db->createCommand(
            "INSERT INTO `metas` 
                        VALUES (1,'Полезные советы на все случаи жизни', 'Полезный совет женщина дом каждый день женский журнал', 'Полезный совет женщина дом каждый день женский журнал')
            ")->execute();
    }
}
