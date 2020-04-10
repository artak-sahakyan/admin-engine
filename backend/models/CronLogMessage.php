<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cron_log_message".
 *
 * @property int $id
 * @property int $cron_log_id
 * @property string $message
 * @property int $created_at
 *
 * @property CronLog $cronLog
 */
class CronLogMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_log_message';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cron_log_id', 'message', 'created_at'], 'required'],
            [['cron_log_id', 'created_at'], 'integer'],
            [['message'], 'string'],
            [['cron_log_id'], 'exist', 'skipOnError' => true, 'targetClass' => CronLog::className(), 'targetAttribute' => ['cron_log_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cron_log_id' => 'Cron Log ID',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCronLog()
    {
        return $this->hasOne(CronLog::className(), ['id' => 'cron_log_id']);
    }
}
