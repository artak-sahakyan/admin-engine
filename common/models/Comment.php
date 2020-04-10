<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string|null $message
 * @property string|null $ip
 * @property string|null $datеtime
 * @property int|null $rating
 * @property string|null $attaches
 * @property int|null $visible
 * @property int|null $user_id
 * @property string|null $nick
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $avatar
 * @property int|null $chat_id
 * @property string|null $url
 * @property string|null $title
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating', 'visible', 'user_id', 'chat_id', 'article_id'], 'integer'],
            [['attaches'], 'safe'],
            [['ip', 'datеtime', 'nick', 'name', 'email', 'phone', 'avatar', 'url', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'ip' => 'Ip',
            'datеtime' => 'Datеtime',
            'rating' => 'Rating',
            'attaches' => 'Attaches',
            'visible' => 'Visible',
            'user_id' => 'User ID',
            'nick' => 'Nick',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'avatar' => 'Avatar',
            'chat_id' => 'Chat ID',
            'url' => 'Url',
            'title' => 'Title',
            'article_id' => 'id article'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $url = explode('-', $this->url);
                $article_id = explode('/', $url[0]);
                $this->article_id = $article_id[3];
            }
            return true;
        } else {
            return false;
        }
    }
}
