<?php
namespace common\behaviors;

use yii\base\Behavior;
use common\models\ArticleHtmlError;

class ValidateArticleBehavior extends Behavior
{
    public function validateHtml()
    {
        $content = $this->owner->content;

        $validator = new \HtmlValidator\Validator();
        $validator->setParser(\HtmlValidator\Validator::PARSER_HTML5);
        $result = $validator->validateNodes($content);
        $errors = $result->getMessages();

        $articleHtmlError = null;
        if($this->owner->htmlErrors) {
            $articleHtmlError = $this->owner->htmlErrors;
            $articleHtmlError->content = null;
        } else {
            $articleHtmlError = new ArticleHtmlError();
            $articleHtmlError->article_id = $this->owner->id;
        }
        
        foreach ($errors as $error) {
            if($error->getType() == 'error' && !mb_stripos($error->getText(), 'CSS') && !mb_stripos($error->getText(), 'longdesc') && !mb_stripos($error->getText(), 'not allowed')) {
                $articleHtmlError->content = $articleHtmlError->content.';'.$error->getText();
            }
        }

        $articleHtmlError->save();

        return $articleHtmlError;
    }   
}
