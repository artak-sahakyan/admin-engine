<?php
$root = $category->parent;
$rootUrl = (isset($root)) ? '/' . $root->slug : '';
$children = $category->childs;
?>

<h1 class="title _1"><?=$category->title?></h1>
<?php if($children): ?>
<div class="dropdown subcategories">
    <?php if(count($children) > 9): ?>
        <input class="dropdown__input" id="dropdown-subcategories" type="checkbox" tabindex="-1" />
        <label class="dropdown__button" for="dropdown-subcategories"></label>
    <?php endif; ?>
    <ul class="subcategories__list">
        <?php foreach ($children as $childs): ?>
            <li class="subcategories__item"><a class="subcategories__link" href="<?=\yii\helpers\Url::to($rootUrl . '/' . $category->slug . '/' . $childs->slug)?>"><?=$childs->title?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>