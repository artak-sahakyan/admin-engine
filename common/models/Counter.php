<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "counters".
 *
 * @property int $id
 * @property string $code
 * @property int $turn_on
 * @property string $title
 * @property int $sort
 */
class Counter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tools_counters}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'sort', 'title'], 'required'],
            [['code'], 'string'],
            [['turn_on', 'sort'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'title' => 'Название',
            'code' => 'Код',
            'turn_on' => 'Активен',
            'sort' => 'Сортировка',
        ];
    }

    public function afterFind()
    {
        $this->code = html_entity_decode($this->code);
        parent::afterFind();
    }
}
