<?php

use yii\db\Migration;

/**
 * Class m190228_042001_update_add_column_banner_group_articles_table
 */
class m190228_042001_update_add_column_banner_group_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'banner_group_id', $this->integer());

        Yii::$app->db->createCommand('UPDATE articles, banner_article_vs_groups
SET articles.banner_group_id = banner_article_vs_groups.banner_group_id
WHERE banner_article_vs_groups.article_id = articles.id')->execute();

       // $this->dropTable('{{%banner_article_vs_groups}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190228_042001_update_add_column_banner_group_articles_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190228_042001_update_add_column_banner_group_articles_table cannot be reverted.\n";

        return false;
    }
    */
}
