<?php

use yii\widgets\ActiveForm;

$form = ActiveForm::begin();

echo $form->field($model, 'content')->widget(\backend\widgets\ckeditor\CKEditor::class, [
    'editorOptions' => [
        'preset' => 'full',
        'fontSize_defaultLabel' => 17,
        'font_defaultLabel' => 'Arial',
        'language'=> 'ru',
        'removePlugins' => 'dialogadvtab,bidi,templates,copyformatting,div,find,flash,forms,iframe,indentblock,smiley,specialchar,language,liststyle,newpage,pagebreak,preview,print,save,selectall,showblocks,scayt,wsc,removeformat',
        'inline' => false,
        'filebrowserBrowseUrl' => 'browse-images',
        'filebrowserUploadUrl' => 'upload-images',
        'extraPlugins' => 'imageuploader,seohide',
    ],
]);
