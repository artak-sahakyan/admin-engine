<?php if($articles): ?>
<noindex>
    <div class="maybe-interesting _with-counters">
        <span class="title _2 maybe-interesting__title">Вам также может быть интересно</span>
        <?= call_user_func(
            array(Yii::$app->params['widgets'] . "InterestingArticlesWidget", 'widget'),
            [
                'articles' => $articles,
                'imageDetectColor' => 'full',
            ]
        );
        ?>
    </div>
</noindex>
<?php endif ?>
