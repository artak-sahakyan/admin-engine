<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "email".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $content
 * @property int $sent
 * @property int $created_at
 * @property int $updated_at
 * @property int $reCaptcha
 */
class Email extends \yii\db\ActiveRecord
{
    public $reCaptcha;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'content'], 'required'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(),
                'uncheckedMessage' => 'Подтвердите что вы не робот',
                'secret' => Yii::$app->params['reCaptcha']['secretV2']],
            [['sent', 'created_at', 'updated_at'], 'integer'],
            [['name', 'email', 'content'], 'string', 'max' => 255],
        ];
    }

    public function send()
    {
        try {
            $first = Yii::$app->mailer->compose()
                ->setFrom($this->email)
                ->setTo(Yii::$app->params['email'])
                ->setSubject('Новое Письмо От ' . $this->name)
                ->setTextBody($this->content)
                ->send();

            $second = true;

            if(isset(Yii::$app->params['email_copy'])) {
                $second = Yii::$app->mailer->compose()
                    ->setFrom($this->email)
                    ->setTo(Yii::$app->params['email_copy'])
                    ->setSubject('Новое Письмо От ' . $this->name)
                    ->setTextBody($this->content)
                    ->send();
            }

            return ($first && $second) ? true : false;

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('swiftEmail', 'Пожалуйста укажите правильные данные');
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'reCaptcha' => 'Подтверждение',
            'content' => 'Текст',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
        ];
    }
}
