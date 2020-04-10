<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admins_groups".
 *
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property string $home_url
 * @property array $allow_actions
 * @property boolean $allow_change_publisher_and_poster
 * @property boolean $show_only_own_posts
 *
 * @property AdminVsGroups[] $adminVsGroups
 */
class AdminGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admins_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias'], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 100],
            [['home_url'], 'string', 'max' => 200],
            [['allow_change_publisher_and_poster'], 'boolean'],
            [['show_only_own_posts'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'title' => 'Title',
            'home_url' => 'Home Url',
            'allow_actions' => 'Allow actions',
            'allow_change_publisher_and_poster' => 'Разрешено изменять постера и публициста',
            'show_only_own_posts' => 'Показать только свои статьи'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminsVsGroups()
    {
        return $this->hasMany(AdminsVsGroups::class, ['group_id' => 'id']);
    }

    public function getAdmins() {
        return $this->hasMany(Admin::class, ['id' => 'admin_id'])
          ->via('adminsVsGroups')->indexBy('id');
    }

    public function getAdminCount()
    {
        return $this->getAdminsVsGroups()->count();
    }

    /**
     * @return bool
     */
    public static function isAdmin()
    {
        $groups = Yii::$app->user->identity->getAdminGroups()->asArray()->all();
        foreach ($groups as $group) {
            if ($group['alias'] == 'admin' && $group['title'] == 'Администратор') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $userHaveGroups
     * @return array
     */
    public static function getAllowActions($userHaveGroups = [])
    {
        $adminGroups = static::find()->where(['id' => $userHaveGroups])->asArray()->all();

        $allowActions = [];
        foreach ($adminGroups as $group) {
            $group['allow_actions'] = json_decode($group['allow_actions'], true);

            if (is_array($group['allow_actions'])) {
                $allowActions = array_merge($allowActions, $group['allow_actions']);
            }
        }

        return $allowActions;
    }

    /**
     * Format string to header
     *
     * @example ArticlePhotoHash return Article Photo Hash
     * @param $header
     * @return null|string|string[]
     */
    public static function showHeader($header)
    {
        return preg_replace_callback('#[A-Z]#', function($matches){
            return ' ' . $matches[0];
        }, $header);
    }

    /**
     * @param $string
     */
    public static function camelToDish($string)
    {
        $string = preg_replace_callback('#[A-Z]#', function($matches){
            return '-' . $matches[0];
        }, $string);

        $string = trim($string, '-');

        return $string;
    }
}
