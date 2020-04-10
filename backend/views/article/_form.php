<?php

use backend\widgets\ckeditor\CKEditor;
use common\models\{ ArticleCategory, Voting };
use yii\helpers\{ Html, ArrayHelper, Url };
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $articleMeta common\models\ArticleMeta */

/* @var $form yii\widgets\ActiveForm */
use kartik\datetime\DateTimePicker;

$contentError = $model->hasErrors('content') ? $model->getErrors('content')[0] : '';

?>
<div class="container-fluid">
    <div class="col-md-8">
        <div class="article-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?php if(empty($model->id)) :?>
                <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'readonly' => true]) ?>
            <?php else: ?>
                <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
            <?php endif ?>

            <?= $form->field($model, 'main_query')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'breadcrumbs')->textInput(['maxlength' => true]) ?>

            <?=$form->field($articleMeta, 'meta_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($articleMeta, 'meta_keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($articleMeta, 'meta_description')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'head_text')->textarea(['rows' => 2]) ?>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Текст статьи</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                            <?=$form->field($model, 'content')->widget(CKEditor::class, [
                                'editorOptions' => [
                                    'preset' => 'full',
                                    'fontSize_defaultLabel' => 17,
                                    'font_defaultLabel' => 'Arial',
                                    'language'=> 'ru',
                                    'width' => '720px',
                                    'removePlugins' => 'dialogadvtab,bidi,templates,copyformatting,div,find,flash,forms,iframe,indentblock,smiley,specialchar,language,liststyle,newpage,pagebreak,preview,print,save,selectall,showblocks,scayt,wsc,removeformat',
                                    'inline' => false,
                                    'filebrowserBrowseUrl' => 'browse-images',
                                    'filebrowserUploadUrl' => 'upload-images',
                                    'extraPlugins' => 'imageuploader,seohide,review,lazyyoutube',
                                ],
                            ])->label(false); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

            <?php if(!$model->isNewRecord): ?>
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Добавить опрос в статью</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?=$this->render('_add_vote', compact('form', 'modelVoting'))?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Анкоры</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <button type="button" style="margin: 10px;color: white" class="btn btn-primary" data-toggle="modal" id="createAnchor" data-id="<?=$model->id?>" data-url="<?=Url::toRoute(['article-anchor/create'])?>" >
                            Добавить анкор
                        </button>
                        <button id="by-text-get-anchors" style="margin: 10px;color: white" data-url="<?= Url::toRoute(['article-anchor/by-text-get-anchors', 'id' => $model->id])?>" class="btn btn-primary" type="button">
                            Запросить анкор с биржи
                        </button>
                        <?=$this->render('_anchors', compact('model'))?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            <?php endif; ?>

            <?php if(!$model->isNewRecord): ?>
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">SEO Анализ</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <button id="seo_analize_button" style="margin: 10px;color: white" data-url="<?= Url::toRoute(['article/update-article-statistic', 'id' => $model->id])?>" class="btn btn-primary" type="button">
                            Анализировать
                        </button>
                        <?=$this->render('_seo_analyse', compact('model'))?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <?= ButtonGroupWidget::widget(['model' => $model]) ?>
                </div>
            </div>



        </div>
    </div>
    <div class="col-md-4">
            <?php if($model->id) :?>
                <div class="panel panel-default ">
                    <div class="panel-body">
                        <div class="col-md-6">
                            <?= $form->field($model, 'id')->textInput(['readonly'=> true])?>
                            <button type="button" class="btn btn-default" onclick="$('#article-id').attr('readonly', !$('#article-id').attr('readonly')); if(!$('#article-id').attr('readonly')) alert('Включен режим смены ID статьи');">Сменить id</button>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'bytextId')->textInput(['readonly'=> true])?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


        <div class="panel panel-default">
            <div class="panel-body">
                <p style="font-weight: bold">Превью статьи</p>
                <img id="thumb-image"  src="<?=$model->getThumb(300, 200)?>?time=<?= time() ?>" >
                <?= $form->field($model, 'imageFile')->fileInput(['style' =>'display:none'])->label(false) ?>
                <input class="btn btn-primary" type="button" id="loadImage" value="Изменить" onclick="document.getElementById('article-imagefile').click();" />
                <button type="button" class="btn btn-warning" id="delImages" data-url="<?= Url::toRoute(['article/delete-images']) . '?id=' . $model->id?>">Удалить</button>
            </div>
        </div>



        <div class="panel panel-default">
            <div class="row">
                <div class="panel-body">
                        <div class="row" style="padding: 0 10px">
                            <div class="col-md-12">
                                <?= $form->field($model, 'is_fix_sidebar')->checkbox(); ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($model, 'is_actual')->checkbox(); ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'is_published', ['template' => '<div style="float:left;margin-right: 10px">{label}</div>{input}'])->checkbox([], false); ?>
                            </div>
                            <div class="col-md-7">
                                <?php $model->timeFormatForDatePicker('published_at'); ?>
                                <?= $form->field($model, 'published_at')->widget(DateTimePicker::class, [
                                    'name' => 'published_at',
                                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                    'options' => ['placeholder' => 'Select operating time ...'],
                                    'convertFormat' => false,
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd hh:ii',
                                        'todayHighlight' => true
                                    ]
                                ])->label(false)?>
                            </div>
                        </div>
                        <div class="row" style="padding: 0 10px">
                            <div class="col-md-4">
                                <?= $form->field($model, 'ready_publish_date', ['template' => '<div style="float:left;margin-right: 10px;padding-top: 5px">{label}</div>'])->label('Гoтовa'); ?>
                            </div>
                            <div class="col-md-7">
                                <?php $model->timeFormatForDatePicker('ready_publish_date'); ?>
                                <?= $form->field($model, 'ready_publish_date', ['template' => '{input}'])->widget(DateTimePicker::class, [
                                    'name' => 'ready_publish_date',
                                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                    'options' => ['placeholder' => ''],
                                    'convertFormat' => false,
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd hh:ii',
                                        'todayHighlight' => true
                                    ]
                                ])->label(false)?>
                            </div>
                        </div>
                        <div class="row" style="padding: 0 10px">
                        <div class="col-md-4">
                            <?= $form->field($model, 'imported_at', ['template' => '<div style="float:left;margin-right: 10px;padding-top: 5px;">{label}</div>'])->label('Импортирована'); ?>
                        </div>
                        <div class="col-md-7">
                            <?php $model->timeFormatForDatePicker('imported_at'); ?>

                            <?= $form->field($model, 'imported_at', ['template' => '{input}'])->widget(DateTimePicker::class, [
                                'name' => 'imported_at',
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'options' => ['placeholder' => ''],
                                'convertFormat' => false,
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true
                                ]
                            ])->label(false)?>
                        </div>
                    </div>
                        <div class="row" style="padding: 0 10px">
                        <div class="col-md-4">
                            <?= $form->field($model, 'checked_anounce_end', ['template' => '<div style="float:left;margin-right: 10px">{label}</div>{input}'])->checkbox([], false); ?>
                        </div>
                        <div class="col-md-7">
                            <?php $model->timeFormatForDatePicker('anounce_end_date'); ?>
                            <?= $form->field($model, 'anounce_end_date', ['template' => '{input}'])->widget(DateTimePicker::class, [
                                'name' => 'anounce_end_date',
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'options' => ['placeholder' => ''],
                                'convertFormat' => false,
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true
                                ]
                            ])->label(false)?>
                        </div>
                    </div>
                        <div class="row" style="padding: 0 10px">
                        <div class="col-md-4">
                            <?= $form->field($model, 'yandex_origin_date', ['template' => '<div style="float:left;margin-right: 0px;margin-top: 10px;">{label}</div>'])->label('Y Origin'); ?>
                        </div>
                        <div class="col-md-7">
                            <?php $model->timeFormatForDatePicker('yandex_origin_date'); ?>
                            <?= $form->field($model, 'yandex_origin_date', ['template' => '{input}'])->widget(DateTimePicker::class, [
                                'name' => 'yandex_origin_date',
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'options' => ['placeholder' => ''],
                                'convertFormat' => false,
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true
                                ]
                            ])->label(false)?>
                        </div>
                        <div class="col-md-12">
                            <button type="button" style="color: white" class="btn btn-primary" onclick="sendYandexOriginal()" >
                            Отправить в оригинальные
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button type="button" style="color: white" class="btn btn-primary"  onclick="clearCache()">
                            Сбросить кеш Айри
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-body">

                <?= $form->field($model, 'parent_category_id')->dropDownList( ArticleCategory::getRootCategoriesList(), [
                    'prompt'=>'Выбрать Родительскую категорию',
                    'onchange'=>'$.get("'.\yii\helpers\Url::toRoute(['article/children-list']).'?id='.'"+$(this).val(),function(data){$("#article-child_category_id").html(data); var html = "\<option\ value=\'\'>Выберите подкатегорию\<\/option\>"; $("#article-subchild_category_id").html(html)})'
                ]) ?>

                <?= $form->field($model, 'child_category_id')->dropDownList($model->parent_category_id ? ArticleCategory::getChildsCategoriesList($model->parent_category_id) : [], [
                     'prompt'=>'Выбрать подкатегорию',
                     'onchange'=>'$.get("'.\yii\helpers\Url::toRoute(['article/children-list']).'?id='.'"+$(this).val(),function(data){$("#article-subchild_category_id").html(data)})'
                ]) ?>


                <?= $form->field($model, 'subchild_category_id')->dropDownList($model->child_category_id ? ArticleCategory::getChildsCategoriesList($model->child_category_id) : [],[
                    'prompt'=>'Выбрать подкатегорию',
                ]) ?>


                <?= $form->field($model, 'banner_group_id')->dropDownList(['' => 'Нет группы'] + \common\models\BannerGroup::find()->select(['name', 'id'])->indexBy('id')->column()) ?>
                <?= Html::checkbox('resetBanners', false, $options = ['label' => 'Сбросить баннеры']) ?>
                <?= $form->field($model, 'expert_id')->dropDownList(\common\models\Expert::find()->select(['username', 'id'])->where(['is_expert' => 1])->indexBy('id')->column(), ['prompt'=>'Не выбран']) ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <?= $form->field($model, 'show_banners')->checkbox(['style' => 'margin-right:10px']); ?>
                <?= $form->field($model, 'is_turbopage')->checkbox(['style' => 'margin-right:10px']); ?>
                <?= $form->field($model, 'send_zen')->checkbox(['style' => 'margin-right:10px']); ?>
                <?= $form->field($model, 'noindex')->checkbox(['style' => 'margin-right:10px']); ?>
                <?= $form->field($model, 'is_double_banner_place_manual_fix')->checkbox(['style' => 'margin-right:10px']); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">

    errors = document.getElementsByClassName('has-error');

    if(errors.length > 0) {
        message = 'Обнаружены ошибки:\n';
        for(i=0; i<errors.length;i++) {
            message += errors[i].getElementsByClassName('help-block')[0].innerText += '\n';
        }
        alert(message);
    }

    var contentError = '<?=$contentError?>';
    if(contentError) {
        alert(contentError);
    }

    var article_id = <?= $model->id ?>;

    function sendYandexOriginal() {

        var data = {
            article_id: article_id
        };

        var url = '<?= \yii\helpers\Url::toRoute(['article/send-yandex-original']) ?>';

        $.get(url, data, function(result) {
            let data = JSON.parse(result);
            let msg = 'отправлено';
            if(data.statusCode != 201) {
                let errors =  JSON.parse(data.content);
                msg = 'Ошибка ' + errors.error_code;
            }

            alert(msg);
        });
        
    }

    function clearCache() {
        var data = {
            article_id: article_id
        };

        var url = '<?= \yii\helpers\Url::toRoute(['article/clear-cache'])  ?>';

        $.get(url, data, function (data) {

            data = JSON.parse(data);

            if (data.status = 200) {
                $msg = 'Кеш сброшен';
            } else {
                $msg = 'Что-то пошло не так';
            }

            alert($msg);

        });
    }

    

</script>
<?php
$this->registerJs("
$('form#w0').submit(function(){
    if($('#article-title').val() == '') {
        alert('Необходимо заполнить «Заголовок (H1)».')
    }
});
CKEDITOR.config.autoParagraph = false;
", \yii\web\View::POS_END);

?>
