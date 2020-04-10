<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles_related_yandex}}`.
 */
class m190124_133231_create_articles_related_yandex_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%articles_related_yandex}}', [
            'id'                 => $this->primaryKey(),
            'article_id'         => $this->integer()->notNull(),
            'related_article_id' => $this->integer()->notNull(),
            'position'           => $this->tinyInteger(3),
            'created_at'        =>  $this->integer(),
            'updated_at'        =>  $this->integer()
        ]);

        $this->addForeignKey('articles_related_yandex_articles', '{{%articles_related_yandex}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('articles_related_yandex_related_articles', '{{%articles_related_yandex}}', 'related_article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%articles_related_yandex}}');
    }
}
