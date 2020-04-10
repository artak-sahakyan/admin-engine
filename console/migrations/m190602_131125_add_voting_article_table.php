<?php

use yii\db\Migration;

/**
 * Class m190602_131125_add_voting_article_table
 */
class m190602_131125_add_voting_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%voting_vs_article}}', [
            'id'            => $this->primaryKey(),
            'voting_id'     => $this->integer()->notNull(),
            'article_id'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-voting-voting_vs_article','{{%voting_vs_article}}','voting_id','{{%votings}}','id','CASCADE');

        $this->addForeignKey('fk-article_category-voting_vs_article','{{%voting_vs_article}}','article_id','{{%articles}}','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190602_131125_add_voting_article_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190602_131125_add_voting_article_table cannot be reverted.\n";

        return false;
    }
    */
}
