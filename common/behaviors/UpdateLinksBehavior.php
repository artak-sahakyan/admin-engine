<?php

namespace common\behaviors;
use common\models\Tag;
use common\models\TagVsArticle;
use yii\base\Behavior;

/**
 * Class UpdateLinks
 *
 * Создает или обновляет связи многие ко многим
 */

class UpdateLinksBehavior extends Behavior
{
    /**
     * @param array $inputIds
     * @param string $relation
     * @param string $relationClassName
     * @return array
     */
    public function updateLinks(array $inputIds, string $relation, string $relationClassName, string $morph = null)
    {
        $existIds   = array_keys($this->owner->$relation);
        $removeIds  = array_diff($existIds, $inputIds);
        $addIds     = array_diff($inputIds, $existIds);

        if(!empty($removeIds)) {

            foreach($removeIds as $removeId) {
               $this->owner->unlink($relation, $this->owner->$relation[$removeId], true);
            }
            
        }

        if(!empty($addIds)) {

            $newModels = $relationClassName::find()->where(['in', 'id', $addIds])->all();

            foreach($newModels as $model) {
                if($morph) {
                    $this->owner->link($relation, $model, ['morph' => $morph]);
                } else {
                    $this->owner->link($relation, $model);
                }
                
            }

        }

        return $this->owner->$relation;
    }
}
