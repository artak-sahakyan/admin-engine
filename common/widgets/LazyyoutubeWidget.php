<?php
namespace common\widgets;

use common\models\ArticleYoutube;
use yii\base\Widget;

/**
 * Виджет
 */
class LazyyoutubeWidget extends Widget
{
    /**
     * @var id in youtube
     */
    public $id;

    /**
     * @var content for web page
     */
    public $forWeb;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $data = [
            'id' => $this->id,
        ];

        if ($this->forWeb) {
            $youtube = ArticleYoutube::find()->where(['link' => $this->id])->limit(1)->one();
            if (isset($youtube)) {
                $data['title'] = $youtube->title;
                $data['cover'] = $youtube->cover;
            }
        }

    	return $this->render('lazyyoutube', $data);
    }
}
