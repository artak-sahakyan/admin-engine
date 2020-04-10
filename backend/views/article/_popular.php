<?php
use yii\widgets\ActiveForm;
use yii\helpers\{Html,Url};

$condition = $popular && is_array($popular);
?>

<h1>Популярные записи</h1>
<div class="box">
        <div class="box-body">
<?php $form = ActiveForm::begin(); ?>

    <div class="custom-control custom-checkbox">
        <input type="hidden" value="0" name="popular_article[show_last]">
        <input type="checkbox" value="1" class="custom-control-input" name="popular_article[show_last]" id="defaultUnchecked" <?= !empty($popular['show_last']) ? 'checked' : '' ?> >
        <label class="control-label" for="defaultUnchecked">Показывать последние</label>
    </div>
    <div id="popular">
        <?php if ($condition): ?>
            <?php foreach ($popular as $i => $one): ?>
                <?php if(!is_numeric($i)) continue; ?>
                <div class="control-group ">
                    <label class="control-label required" for="popular_article_<?= $i; ?>">ID Популярной записи <span
                                class="required">*</span></label>
                    <div class="controls">
                        <input data-id="<?= $i; ?>" type="text" name="popular_article[<?= $i; ?>]" value="<?php echo $one; ?>"
                               id="popular_article_<?= $i; ?>">
                    </div>
                    <div class="controls"><a href="#" class="removeBannerCode">Удалить запись</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif ?>
    </div>


<button id="addPopular" class="btn btn-success" type="button">Добавить запись</button>

<div class="buttons" style="margin-top: 30px;">
    <?=Html::a('<i class="fa fa-fw fa-close"></i> Отмена', Url::to('/admin'), ['class' => 'btn btn-default', 'title' => 'Отменить и вернуться назад']) ?>
    <?=Html::button('<i class="fa fa-fw fa-save"></i> Сохранить', ['class' => 'btn btn-primary', 'name' => 'apply', 'value' => 'true', 'type' => 'submit', 'title' => 'Применить изменения']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>
</div>