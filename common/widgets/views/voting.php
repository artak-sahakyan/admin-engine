<?php if(!empty($voting)): ?>
<h2>Опрос</h2>
<form method="GET" action="/voting/save" class="poll _question">
    <div class="poll__title">Опрос</div>
    <div class="poll__question"><?= $voting->title ?></div>
    <div class="poll__body _question" data-style="question">
        <?php if ($voting->answers): ?>
            <?php foreach ($voting->answers as $answer): ?>
                <label class="radio poll__answer">
                    <input class="radio__input" type="radio" name="answer" value="<?= $answer->id ?>">
                    <span class="radio__label"><?= $answer->title ?></span>
                </label>
            <?php endforeach; ?>
            <button class="button poll__button" type="submit">Ответить</button>
        <?php endif; ?>
    </div>
    <div class="poll__body _thanks" hidden data-style="thanks"><span class="poll__thanks">Спасибо за ответ</span>
        <button class="button poll__button _show-results">Посмотреть результаты</button>
    <?php /* if(!empty($article)): ?>
    <div class="social _counters poll__social"><span class="social__title">Поделитесь с друзьями</span>
        <div class="social__items">
            <a href="#" title="" onClick="<?= $vkShareButtonOnClick ?>" class="social__item _vk" aria-label="Вконтакте">
                <svg class="social__item-icon icon _vk" width="20px" height="20px">
                    <use xlink:href="<?= Yii::getAlias('@web') . '/images/icons-sprite.svg#vk' ?>"></use>
                </svg>
                <span class="social__counter"><?= rand(0, 10) ?></span>
            </a>
            <a href="#" title="" onClick="<?= $twitterShareButtonOnClick ?>" class="social__item _twitter">
                <svg class="social__item-icon icon _ok" width="20px" height="20px">
                    <use xlink:href="images/icons-sprite.svg#twitter"></use>
                </svg>
                <span class="social__counter"><?= rand(0, 10) ?></span>
            </a>
            <a href="#" title="" onClick="<?= $okShareButtonOnClick ?>" aria-label="Одноклассники" class="social__item _ok">
                <svg class="social__item-icon icon _ok" width="20px" height="20px">
                    <use xlink:href="<?= Yii::getAlias('@web') . '/images/icons-sprite.svg#ok' ?>"></use>
                </svg>
                <span class="social__counter"><?= rand(0, 10) ?></span>
            </a>
        </div>
    </div>
    <?php endif; */?>
    </div>

    <div class="poll__body _results" hidden data-style="results">
        <div class="poll__result" style="--value: 23%;"><span class="poll__label">До 5 кг</span><span class="poll__value">23%</span>
        </div>
        <div class="poll__result" style="--value: 23%;"><span class="poll__label">До 50 кг</span><span class="poll__value">23%</span>
        </div>
        <div class="poll__result" style="--value: 15%;"><span class="poll__label">До 55 кг</span><span class="poll__value">15%</span>
        </div>
        <div class="poll__result" style="--value: 16%;"><span class="poll__label">Свыше 20 кг</span><span class="poll__value">16%</span>
        </div>
        <div class="poll__result" style="--value: 23%;"><span class="poll__label">Не получалось похудеть</span><span class="poll__value">23%</span>
        </div>
        <div class="social _counters poll__social"><span class="social__title">Поделиться с друзьями</span>
            <div class="social__items">
                <a class="social__item _vk" href="#" aria-label="Вконтакте">
                    <svg class="social__item-icon icon _vk" width="20px" height="20px">
                        <use xlink:href="images/icons-sprite.svg#vk"></use>
                    </svg>
                    <span class="social__counter">73</span>
                </a>
                <a class="social__item _ok" href="#" aria-label="Одноклассники">
                    <svg class="social__item-icon icon _ok" width="20px" height="20px">
                        <use xlink:href="images/icons-sprite.svg#ok"></use>
                    </svg>
                    <span class="social__counter">131</span>
                </a>
            </div>
        </div>
    </div>
</form>
<?php endif; ?>