<div class="social <?= $classname ?>">
    <?php if ($title): ?>
        <span class="social__title"><?=$title?></span>
    <?php endif; ?>
    <div class="social__items">
        <?php if(isset($actions['Vk'])): ?>
        <a class="social__item _vk" onClick="<?= $actions['Vk'] ?>" href="#">
            <svg class="social__item-icon icon _vk" width="20px" height="20px">
                <use xlink:href="/images/icons-sprite.svg#vk"></use>
            </svg>
            Рассказать ВКонтакте
        </a>
        <?php endif; ?>
        <?php if(isset($actions['Ok'])): ?>
        <a class="social__item _ok" onClick="<?= $actions['Ok'] ?>" href="#">
            <svg class="social__item-icon icon _ok" width="20px" height="20px">
                <use xlink:href="/images/icons-sprite.svg#ok"></use>
            </svg>
            Поделиться в Одноклассниках
        </a>
        <?php endif; ?>
        <?php if(isset($actions['Facebook'])): ?>
        <a class="social__item _facebook" onClick="<?= $actions['Facebook'] ?>" href="#">
            <svg class="social__item-icon icon _facebook" width="20px" height="20px">
                <use xlink:href="/images/icons-sprite.svg#facebook"></use>
            </svg>
            Поделиться в Facebook
        </a>
        <?php endif; ?>
        <?php if(isset($actions['Telegram'])): ?>
        <a class="social__item _telegram" onClick="<?= $actions['Telegram'] ?>" href="#">
            <svg class="social__item-icon icon _telegram" width="38px" height="38px">
                <use xlink:href="/images/icons-sprite.svg#telegram"></use>
            </svg>
            Поделиться в Telegram
        </a>
        <?php endif; ?>
        <?php if(isset($actions['Viber'])): ?>
        <a class="social__item _viber" onClick="<?= $actions['Viber'] ?>" href="#">
            <svg class="social__item-icon icon _viber" width="100px" height="100px">
                <use xlink:href="/images/icons-sprite.svg#viber"></use>
            </svg>
            Поделиться в Viber
        </a>
        <?php endif; ?>
        <?php if(isset($actions['WA'])): ?>
        <a class="social__item _whatsapp" onClick="<?= $actions['WA'] ?>" href="#">
            <svg class="social__item-icon icon _whatsapp" width="90px" height="90px">
                <use xlink:href="/images/icons-sprite.svg#whatsapp"></use>
            </svg>
            Поделиться в WhatsApp
        </a>
        <?php endif; ?>
    </div>
</div>
