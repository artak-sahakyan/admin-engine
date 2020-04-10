<?php

use yii\helpers\{ Html, ArrayHelper };
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\widgets\ButtonGroupWidget;
use common\models\{
    BannerGroup,
    ArticleCategory
};

/* @var $this yii\web\View */
/* @var $model common\models\RssChannel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rss-channel-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Настройки</a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Фильтры</a></li>
          <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Wiki</a></li>
      </ul>
      <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
           <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

           <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

           <?= $form->field($model, 'container_template')->textarea(['rows' => 6]) ?>

           <?= $form->field($model, 'item_template')->textarea(['rows' => 6]) ?>

           <?= $form->field($model, 'image_template')->textarea(['rows' => 2]) ?>

           <?= $form->field($model, 'limit')->textInput() ?>

            </div>

           <div class="tab-pane" id="tab_2">
                <div class="iradio_minimal-blue">
                    <?= $form->field($model, 'is_published')->radioList([0 => 'Не важно', 1 => 'Вкл', 2 => 'Выкл'])->label('Опубликованные статьи') ?>
                </div>

                <div class="iradio_minimal-blue">
                    <?= $form->field($model, 'is_turbopage')->radioList([0 => 'Не важно', 1 => 'Вкл', 2 => 'Выкл'])->label('Турбостраница') ?>
                </div>

                <div class="iradio_minimal-blue">
                    <?= $form->field($model, 'send_zen')->radioList([0 => 'Не важно', 1 => 'Вкл', 2 => 'Выкл'])->label('Отправка в Дзен') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'bannerGroups')->widget(Select2::class, [
                        'data' => ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 100
                        ],
                    ]);
                    ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'articleCategories')->widget(Select2::class, [
                        'data' => ArrayHelper::map(ArticleCategory::find()->asArray()->all(), 'id', 'title'),
                        'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 100
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="tab-pane" id="tab_3">
            <ul>
                <li><b>{{container}}</b> - главный контейнер</li>
                <li><b>{{url}}</b> - урл статьи</li>
                <li><b>{{title}}</b> - заголовок</li>
                <li><b>{{slug}}</b> - транслит заголовка</li>
                <li><b>{{published_at}}</b> - дата публикации</li>
                <li><b>{{contentWithoutBanners}}</b> - наполнение статьи без баннеров и опросов</li>
                <li><b>{{images}}</b> - контейнер для изображений из статьи</li>
                <li><b>{{src}}</b> - урл изображения</li>
            </ul>
            </div>
    <div class="row">
        <div class="col-md-12">
            <?= ButtonGroupWidget::widget(['model' => $model]) ?>
        </div>
    </div>
</div>

</div>






</div>

<?php ActiveForm::end(); ?>

</div>
