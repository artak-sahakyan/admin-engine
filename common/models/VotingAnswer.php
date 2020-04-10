<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voting_answers".
 *
 * @property int $id
 * @property string $title
 * @property int $voting_id
 * @property int $count
 *
 * @property Votings $voting
 */
class VotingAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%voting_answers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'voting_id'], 'required'],
            [['voting_id', 'count'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['voting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::class, 'targetAttribute' => ['voting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'voting_id' => 'Voting ID',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoting()
    {
        return $this->hasOne(Voting::class, ['id' => 'voting_id']);
    }
}
