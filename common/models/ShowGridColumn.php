<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "show_grid_columns".
 *
 * @property int $id
 * @property int $grid_id
 * @property string $attribute
 * @property int $is_checked
 *
 */
class ShowGridColumn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'show_grid_columns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grid_id'], 'required'],
            [['grid_id', 'is_checked'], 'integer'],
            [['attribute'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grid_id' => 'Grid ID',
            'attribute' => 'Attribute',
            'is_checked' => 'Is Checked',
        ];
    }
}
