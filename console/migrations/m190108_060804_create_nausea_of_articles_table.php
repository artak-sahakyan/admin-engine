<?php

use yii\db\Migration;

/**
 * Handles the creation of table `nausea_of_article`.
 */
class m190108_060804_create_nausea_of_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nausea_of_articles}}', [
            'id'            => $this->primaryKey(),
            'article_id'    => $this->integer(),
            'chapters'      => $this->float(),
            'h1'            => $this->float(),
            'title'         => $this->float(),
            'description'   => $this->float(),
            'keywords'      => $this->float(),
            'alt'           => $this->float(),
            'text'          => $this->float(),
            'baden_points'  => $this->float(),
            'bigram'        => $this->float(),
            'trigram'       => $this->float(),
            'word_density'  => $this->float()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');


        $this->createIndex('nausea-of-articlesId', '{{%nausea_of_articles}}', 'article_id');
        $this->addForeignKey('nausea-of-articles-articles', '{{%nausea_of_articles}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%nausea_of_article}}');
    }
}
