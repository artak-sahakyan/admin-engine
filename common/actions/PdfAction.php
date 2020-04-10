<?php
namespace common\actions;

use yii\base\Action;
use kartik\mpdf\Pdf;
use common\behaviors\ContentProcessBehavior;
use common\models\Article;

class PdfAction extends Action
{
    public function run($url) {
        $url = explode('-', $url);
        $id = array_shift($url);
        $article = Article::find()->where(['id' => $id])->published()->with('articleMeta', 'expert')->one();

        if(!isset($id) || empty($article) || ($article->slug != implode('-', $url))) throw new NotFoundHttpException(404);

        $article->attachBehaviors([
            ContentProcessBehavior::class
        ]);

        $article->contentProcessors([
            'votings'   => false,
            'videos'    => false,
            'related'   => false,
            'turbo'     => false,
            'amp'       => false,
            'banners'   => false
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $article->displayContent,  
            'options' => ['title' => $article->getUrl()],
            'methods' => [ 
                'SetHeader'=>[$article->title], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        return $pdf->render(); 
    }
}
