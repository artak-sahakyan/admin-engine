<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "common_data".
 *
 * @property int $id
 * @property string $name
 * @property array $value
 * @property int $created_at
 * @property int $updated_at
 */
class CommonData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_data';
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
            [['name'], 'required'],
            [['value'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $key
     * @param null $value
     * @return CommonData ActiveRecord|null
     */
    public static function value($name, $value = null)
    {
        $model = self::findOne(['name' => $name]);

        if(is_null($model)) {
            $model = new self(['name' => $name]);
        }

        if(!is_null($value)) {
            $model->value = $value;
            $model->save();
        }

        if(!is_array($model->value)) {
            $model->value = json_decode($model->value, true);
        }

        return $model->value;
    }
}
