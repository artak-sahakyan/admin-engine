<?php

namespace common\models;

use common\models\AdminGroup;
use common\models\Article;
use Yii;
use yii\base\NotSupportedException;
use yii\base\Security;
use yii\behaviors\TimestampBehavior;
use common\behaviors\UpdateLinksBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admins}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $group_id
 * @property int $register_date
 * @property int $last_login_date
 * @property int $is_active
 * @property int $register_ip
 * @property string $settings
 * @property int $restrict_by_ip
 * @property int $ips
 * @property int $password
 *
 *
 * @property Article[] $articles
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{

    public $password;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ADMIN = 'admin';
    const SEO = 'seo';
    //const POSTER = 'poster';
    const POSTER = 'kontentcshik';
    //const PUBLISHER = 'publisher';
    const PUBLISHER = 'publicist';
    const CORRECTOR = 'corrector';


    public function behaviors()
    {
        return [
            UpdateLinksBehavior::class,
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admins}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'register_date', 'last_login_date', 'is_active', 'restrict_by_ip'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username', 'email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['password'], 'string', 'min' => 6],
            [['settings',  'register_date, last_login_date, is_active', 'restrict_by_ip', 'ips', 'password'], 'safe'],
            [['status'], 'default', 'value' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'adminGroups'    => 'Группы',
            'register_date'     => 'Дата регистрации',
            'last_login_date'   => 'Последний вход',
            'is_active'         => 'Активность',
            'register_ip'       => 'register_ip',
            'settings'          => 'settings',
            'restrict_by_ip'    => 'restrict_by_ip',
            'ips'               => 'ips',
            'password'          => 'Пароль',

        ];
    }

    public function beforeSave($insert) {
        if(isset($this->password)) {
            $this->setPassword($this->password);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['admin_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticlesPublisher()
    {
        return $this->hasMany(Article::class, ['publisher_id' => 'id']);
    }

    public function getArticlesCount() {
        return $this->getArticles()->count(); 
    }

    public function getArticlesPublisherCount() {
        return $this->getArticlesPublisher()->count(); 
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($username)
    {
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if(md5($password) == $this->password_hash) return true;
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // public function getAdminGroup() {
    //     return $this->hasOne(AdminsGroup::class, ['id' => 'group_id']);
    // }

    public function getAdminsVsGroups()
    {
        return $this->hasMany(AdminsVsGroups::class, ['admin_id' => 'id']);
    }

    public function getAdminGroups() {
        return $this->hasMany(AdminGroup::class, ['id' => 'group_id'])
          ->via('adminsVsGroups')->indexBy('id');
    }


    public static function getEmployeeList($employee) {

        return  static::find()
            ->select([self::tableName() . '.username', self::tableName() . '.id'])
            ->innerJoinWith('adminGroups')
            ->andWhere([AdminGroup::tableName() . '.alias' => $employee])
            ->andWhere([self::tableName() . '.is_active' => 1])
            ->indexBy('id')
            ->column();

    }

    public function allowedChangePublisherAndPoster()
    {
        $allowChangePublisherAndPoster = false;
        foreach ($this->getAdminGroups()->all() as $group) {
            if ($group->allow_change_publisher_and_poster) {
                $allowChangePublisherAndPoster = true;
            }
        }
        return $allowChangePublisherAndPoster;
    }

    public function showOnlyOwnPosts()
    {
        $show_only_own_posts = true;
        foreach ($this->getAdminGroups()->all() as $group) {
            if (!$group->show_only_own_posts) {
                $show_only_own_posts = false;
            }
        }
        return $show_only_own_posts;
    }
}
