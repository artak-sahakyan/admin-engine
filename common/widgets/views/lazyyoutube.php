<?php if (!empty($title) && !empty($cover)): ?>
    <a class="youtube" target="_blank" href="https://www.youtube.com/watch?v=<?= $id ?>">
        <img class="youtube__cover" alt="title" src="<?= $cover ?>" />
        <span class="youtube__title"><?= $title ?></span>
        <button formaction="#" class="youtube__play" type="button">Смотреть видео</button>
    </a>
<?php else: ?>
    <iframe allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen=""
            frameborder="0"
            height="350"
            src="https://www.youtube.com/embed/<?= $id ?>"
            width="700"></iframe>
<?php endif ?>
