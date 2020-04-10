<?php
namespace common\actions;

use Yii;
use yii\base\Action;
use common\models\VotingAnswer;

class VotingAction extends Action
{

    public function run() {
        $request = Yii::$app->request;
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');

        if ($id = $request->get('answer')) {
            if ($answer = VotingAnswer::findOne($id)) {
                $answer->count += 1;
                $answer->save();

                $answersCounter = (VotingAnswer::findBySql("SELECT SUM(count) AS count FROM `voting_answers` WHERE `voting_id` = $answer->voting_id")->column())[0];

                $answers = VotingAnswer::find()->where(['voting_id' => $answer->voting_id])->all();

                $count = $answersCounter ? $answersCounter : 0;

                $response = [];
                foreach ($answers as $key => $item) {
                    $percent = $count != 0 ?  $item->count * 100 / $count : 0;
                    $response[$key]['text'] = $item->title;
                    $response[$key]['percent'] = round($percent);
                }

                return json_encode($response);
            }
        }

        return false;
    }
}