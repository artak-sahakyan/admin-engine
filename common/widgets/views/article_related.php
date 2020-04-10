<?php if($articles): ?>
    <div class="similar-articles__column">
        <?php if($title): ?><span class="title _3"><?= $title ?></span><?php endif; ?>
        <ul class="similar-articles__list">
            <?php foreach ($articles as $article): ?>
            <li class="similar-articles__item"><a class="similar-articles__link" href="<?=$article->getUrl()?>"><?=$article->title?></a>
            </li>
            <?php endforeach?>
        </ul>
    </div>
<?php endif ?>
