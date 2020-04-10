<?php

use yii\db\Migration;

/**
 * Class m190703_052845_remove_votings_table
 */
class m190703_052845_remove_votings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");
        
        $this->dropTable('{{%voting_vs_banner_groups}}');
        $this->dropTable('{{%voting_vs_article_categories}}');
        $this->dropTable('{{%voting_vs_article}}', 'voting_links');

        $this->createTable('{{%voting_links}}', [
            'id'                    => $this->primaryKey(),
            'voting_id'             => $this->integer()->notNull(),
            'link_id'               => $this->integer()->notNull(),
            'morph'                 => $this->string(50),
        ]);

        $this->addForeignKey('fk-voting_voting-links','{{%voting_links}}','voting_id','{{%votings}}','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190703_052845_remove_votings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_052845_remove_votings_table cannot be reverted.\n";

        return false;
    }
    */
}
