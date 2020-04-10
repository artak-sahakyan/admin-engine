<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%votings}}`.
 */
class m190314_132155_create_votings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%votings}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(50)->notNull()->unique(),
            'title'         => $this->string(255)->notNull(),
            'show_sidebar'  => $this->boolean()->notNull()->defaultValue(false),
            'show_bottom'   => $this->boolean()->notNull()->defaultValue(false),
            'show_main'     => $this->boolean()->notNull()->defaultValue(false)
        ]);

        $this->createTable('{{%voting_answers}}', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string(255)->notNull(),
            'voting_id'     => $this->integer()->notNull(),
            'count'         => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable('{{%voting_vs_article_categories}}', [
            'id'                    => $this->primaryKey(),
            'voting_id'             => $this->integer()->notNull(),
            'article_category_id'   => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%voting_vs_banner_groups}}', [
            'id'                    => $this->primaryKey(),
            'voting_id'             => $this->integer()->notNull(),
            'banner_group_id'       => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-voting_answers-voting','{{%voting_answers}}','voting_id','{{%votings}}','id','CASCADE');

        $this->addForeignKey('fk-voting-voting_vs_article_categories','{{%voting_vs_article_categories}}','voting_id','{{%votings}}','id','CASCADE');
        $this->addForeignKey('fk-voting-voting_vs_banner_groups','{{%voting_vs_banner_groups}}','voting_id','{{%votings}}','id','CASCADE');

        $this->addForeignKey('fk-article_category-voting_vs_article_categories','{{%voting_vs_article_categories}}','article_category_id','{{%article_categories}}','id','CASCADE');
        $this->addForeignKey('fk-article_category-voting_vs_banner_groups','{{%voting_vs_banner_groups}}','banner_group_id','{{%banner_groups}}','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%votings}}');
    }
}
