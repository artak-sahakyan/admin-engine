<div class="sources">
	<p>Источники:</p>
	<ul>
		<?php foreach ($items as $item): ?>
	    <li><a target="_blank" rel="nofollow" href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
		<?php endforeach?>
	</ul>
</div>
