<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manual_responses".
 *
 * @property int $id
 * @property string $url
 * @property int $code
 * @property string $redirect_url
 */
class ManualResponse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manual_responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'integer'],
            [['url', 'redirect_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'code' => 'Code',
            'redirect_url' => 'Redirect Url',
        ];
    }
}
