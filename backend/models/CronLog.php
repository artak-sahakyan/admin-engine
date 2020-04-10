<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cron_log".
 *
 * @property int $id
 * @property string $command
 * @property string $status
 * @property int $progress
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CronLogMessage[] $cronLogMessages
 */
class CronLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_log';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['command', 'status', 'progress', 'created_at', 'updated_at'], 'required'],
            [['status'], 'string'],
            [['progress', 'created_at', 'updated_at'], 'integer'],
            [['command'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command' => 'Command',
            'status' => 'Status',
            'progress' => 'Progress',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function addLogMessage($message)
    {
        $cronLogMessage = new CronLogMessage();
        $cronLogMessage->cron_log_id = $this->id;
        $cronLogMessage->message = $message;
        $cronLogMessage->save(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCronLogMessages()
    {
        return $this->hasMany(CronLogMessage::class, ['cron_log_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCronLogMessagesSize($cronLogId)
    {
        return CronLogMessage::find()->where(['cron_log_id' => $cronLogId])->count();
    }


    /**
     * @param string $command
     * @return bool
     */
    public static function checkCommandRunningStatus(string $command) {
        return ($cronLog = self::checkLastRunByCommand($command)) ? $cronLog['status'] == 'running' : false;
    }

    /**
     * @param string $command
     * @return null|\yii\db\ActiveRecord object
     */
    public static function checkLastRunByCommand(string $command) {
        return static::find()
            ->where(['command' => $command])
            ->orderBy('created_at DESC')
            ->one();
    }

}
