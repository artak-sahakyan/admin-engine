<?php
use common\widgets\SocialsWidget;
?>
<?php if($contents): ?>
<noindex>
<div class="dropdown contents">
    <input class="dropdown__input" id="dropdown-contents" type="checkbox" tabindex="-1" />
    <label class="dropdown__button" for="dropdown-contents"></label>
    <span class="contents__title">Содержание</span>
    <ul class="contents__list">
        <?php foreach($contents as $index => $item):?>
            <li class="contents__item">
                <a class="contents__link" href="#<?=$item->href?>">
                    <?=$index?>. <?=$item->label?>
                </a>
            </li>
            <?php if(isset($item->childs)):?>
                <?php foreach($item->childs as $index2 => $subchild):?>
                <li class="contents__item">
                    <a class="contents__link" href="#<?=$subchild->href?>">
                        <?=$index.'.'.$index2?>. <?=$subchild->label?>
                    </a>
                </li>
            <?php endforeach?>
            <?php endif?>
        <?php endforeach?>
    </ul>
</div>
<?= SocialsWidget::widget(['article' => $article, 'classname' => '_buttons _colorized', 'socials' => ['Vk', 'Ok', 'Facebook']]); ?>
</noindex>
<?php endif; ?>

<?php $file = (Yii::getAlias('@device_id') != 3) ? 9 : 13; ?>
<?= common\widgets\SeohideWidget::widget([
    'title' => "<span style=\"display: inline-block; width: 100%;\">
    <object style=\"position: relative; z-index: -1; width: 100%;\" type=\"image/svg+xml\" data=\"" . Yii::getAlias('@web') . '/images/zen/Group_' .  $file . '.svg' . "\">
    </object>
    </span>",
    'url' => 'https://zen.yandex.ru/sovets.net',
    'options' => ['style' => 'border-bottom: none; display: inline-block; position: relative; z-index: 1; width: 100%;'],
    'target' => '_blank'
]); ?>