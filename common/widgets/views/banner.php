<div class="full-place" data-place="<?= $alias ?>" style="width: 100%;">
	<?php if($similarArticles): ?>
		<noindex class="similar-articles">
			<?= $similarArticles ?>
			<div class="similar-articles__column offer__container"><?= $bannerCode ?></div>
		</noindex>
	<?php else: ?>
    <?= $bannerCode ?>
    <?php endif; ?>
</div>
