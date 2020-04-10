<?php

use yii\db\Migration;

/**
 * Class m181220_125712_create_banners
 */
class m181220_125712_create_banners extends Migration
{

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

        //Tables
        $this->createTable('{{%banner_banners}}', [
            'id'            => $this->primaryKey(),
            'type_id'       => $this->integer()->notNull()->defaultValue(1),
            'is_active'     => $this->boolean()->notNull()->defaultValue(false),
            'is_scroll_fix' => $this->boolean()->notNull()->defaultValue(false),
            'name'          => $this->string(50)->notNull()->unique(),
            'place_id'      => $this->integer()->null(),
            'device_id'     => $this->integer()->null(),
            'group_id'      => $this->integer()->defaultValue(0),
            'content'       => $this->text()->null(),
            'created_at'    => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%banner_places}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(50)->notNull()->unique(),
            'alias' => $this->string(50)->notNull()->unique(),
            'container' => $this->text()->null(),
        ], $tableOptions);

        $this->createTable('{{%banner_types}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(70)->notNull()->unique(),
            'slug'  => $this->string(70)->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%banner_devices}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(70)->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%banner_groups}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(70)->notNull()->unique(),
            'show_default_group' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->createTable('{{%banner_article_vs_groups}}', [
            'id'                => $this->primaryKey(),
            'article_id'        => $this->integer()->notNull()->unique(),
            'banner_group_id'   => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%banner_partners}}', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(70)->notNull()->unique(),
            'alias' => $this->string(50)->notNull()->unique()
        ], $tableOptions);

        //Indexes
        $this->createIndex('idx-banners-banner_types','{{%banner_banners}}','type_id');
        $this->createIndex('idx-banner_place_alias','{{%banner_places}}','alias');

        $this->createTable('{{%banner_vs_partners}}', [
            'id'            => $this->primaryKey(),
            'banner_id'     => $this->integer()->notNull(),
            'partner_id'    => $this->integer()->notNull(),
        ], $tableOptions);

        //ForeignKeys
        $this->addForeignKey('fk-banners-banner_types','{{%banner_banners}}','type_id','{{%banner_types}}','id','CASCADE');
        $this->addForeignKey('fk-banner_vs_partners-banner','{{%banner_vs_partners}}','banner_id','{{%banner_banners}}','id','CASCADE');
        $this->addForeignKey('fk-banner_vs_partners-partner','{{%banner_vs_partners}}','partner_id','{{%banner_partners}}','id','CASCADE');

        $this->addForeignKey('fk-banner_article_vs_groups-article','{{%banner_article_vs_groups}}','article_id','{{%articles}}','id','CASCADE');
        $this->addForeignKey('fk-banner_article_vs_groups-banner_group','{{%banner_article_vs_groups}}','banner_group_id','{{%banner_groups}}','id','CASCADE');
    
        //Inserts
        $this->batchInsert('banner_devices', ['name'], [
          ['Десктоп'],
          ['Мобильные'],
          ['Планшет'],
        ]);

        $this->batchInsert('banner_types', ['name', 'slug'], [
          ['Код', 'code'],
          ['Оффер', 'offer'],
        ]);

        $this->batchInsert('banner_groups', ['name'], [
          ['Default'],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%banner_banners}}');
        $this->dropTable('{{%banner_places}}');
        $this->dropTable('{{%banner_types}}');
        $this->dropTable('{{%banner_devices}}');
        $this->dropTable('{{%banner_partners}}');
        $this->dropTable('{{%banner_groups}}');
        $this->dropTable('{{%banner_vs_partners}}');
        $this->dropIndex('idx-banners-banner_types','{{%banner_banners}}');
        $this->dropForeignKey('fk-banner_vs_partners-banner','{{%banner_vs_partners}}');
        $this->dropForeignKey('fk-banner_vs_partners-partner','{{%banner_vs_partners}}');
        $this->dropForeignKey('fk-banner_article_vs_groups-article','{{%banner_article_vs_groups}}');
        $this->dropForeignKey('fk-banner_article_vs_groups-banner_group','{{%banner_article_vs_groups}}');
    }
}
