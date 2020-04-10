<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admins_groups}}`.
 */
class m190205_151100_create_admins_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
       $this->createTable('{{%admins_groups}}', [
            'id'        => $this->primaryKey(),
            'alias'     => $this->string(40),
            'title'     => $this->string(100),
            'home_url'  => $this->string(200),
        ]);

        Yii::$app->db->createCommand(
            'INSERT INTO `admins_groups` (`id`, `alias`, `title`, `home_url`) VALUES
            (1, \'admin\', \'Администратор\', \'article/article\'),
            (12, \'seo\', \'SEO\', \'seo/content\'),
            (13, \'kontentcshik\', \'Постер\', \'article/article\'),
            (16, \'publicist\', \'Публицист\', \'article/unpublishArticle\'),
            (17, \'corrector\', \'Корректор\', \'article/article\'),
            (19, \'konsultant-po-dietologii\', \'Консультант по Диетологии\', \'services/dietconsult/index\'),
            (20, \'marketer\', \'Маркетолог\', \'article/article\')')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%admins_groups}}');
    }
}
