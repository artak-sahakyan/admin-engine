<?php

use yii\db\Migration;

/**
 * Class m190207_103358_add_fields_to_meta_table
 */
class m190207_103358_add_fields_to_meta_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%article_metas}}', 'words', $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext'));
        $this->addColumn('{{%article_metas}}', 'main_word', $this->string());
        $this->addColumn('{{%article_metas}}', 'unused_metrika_words_count', $this->integer());
        $this->addColumn('{{%article_metas}}', 'metrika_words_count', $this->integer());
        $this->addColumn('{{%article_metas}}', 'unique_users_yesterday_count', $this->integer());
        $this->addColumn('{{%article_metas}}', 'unique_users_all_time_count', $this->integer());
        $this->addColumn('{{%article_metas}}', 'updated_at', $this->integer());

        Yii::$app->db->createCommand('ALTER TABLE `article_metas` ADD INDEX( `article_id`)')->execute();
        Yii::$app->db->createCommand('ALTER TABLE `article_metas` ADD CONSTRAINT `article_metas_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190207_103358_add_fields_to_meta_table cannot be reverted.\n";

        return false;
    }

}
