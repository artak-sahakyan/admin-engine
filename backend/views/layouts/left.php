<?php

use \common\models\AdminGroup;
use \common\helpers\DomainHelper;
$actionId = \Yii::$app->controller->action->id;
$pageId = \Yii::$app->controller->id;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?php
            $isAdmin = false;
            $allowActions = [];
            if (!Yii::$app->user->isGuest) {
                $userHaveGroups = \Yii::$app->user->identity->getAdminGroups()->asArray()->all();
                $allowActions = AdminGroup::getAllowActions($userHaveGroups);
                $isAdmin = AdminGroup::isAdmin();
            }
        ?>

        <?php
            $menu = [
                'options' => [
                    'class' => 'sidebar-menu tree',
                    'data-widget'=> 'tree',
                    'allowActions' => $allowActions,
                    'isAdmin' => $isAdmin,
                ],
                'items' => [
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Публикации',
                        'icon' => 'newspaper',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Опубликованные', 'icon' => 'check-circle', 'url' => ['/article'],],
                            ['label' => 'Неопубликованные', 'icon' => 'times-circle', 'url' => ['/article/unpublished'],],
                            ['label' => 'Популярные статьи', 'icon' => 'times-circle', 'url' => ['/article/popular'],],
                            ['label' => 'Категории', 'icon' => 'align-justify', 'url' => ['/article-category'],],
                            ['label' => 'Интеграции', 'icon' => 'external-link-alt', 'url' => ['/article-advertising-integration'],],
                            ['label' => 'Рейтинги', 'icon' => 'star', 'url' => ['/article-rating'],],
                            ['label' => 'Эксперты', 'icon' => 'users', 'url' => ['/expert'],],
                        ],
                    ],
                    [
                        'label' => 'Контентные блоки',
                        'icon' => 'code',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Счетчики', 'icon' => 'tachometer-alt', 'url' => ['/counter']]
                        ],
                    ],
                    [
                        'label' => 'SEO',
                        'icon' => 'search',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Перелинковка', 'icon' => 'random', 'url' => ['/article/related-yandex-articles'],],
                            ['label' => 'Опросы', 'icon' => 'chart-bar', 'url' => ['/voting'],],
                            ['label' => 'RSS', 'icon' => 'rss', 'url' => ['/rss-channel'],],
                            ['label' => 'Анализ заголовков', 'icon' => 'header', 'url' => ['/article/headers'],],
                            ['label' => 'Комментарии', 'icon' => 'comments', 'url' => ['/comment'],]
                        ],
                    ],
                    [
                        'label' => 'Реклама',
                        'icon' => 'window-maximize',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Баннеры', 'icon' => 'window-maximize', 'url' => ['/banner'],],
                            ['label' => 'Рекламные места', 'icon' => 'map-marker', 'url' => ['/banner-place'],],
                            ['label' => 'Группа статей', 'icon' => 'object-group', 'url' => ['/banner-group'],],
                            ['label' => 'Партнерка', 'icon' => 'handshake', 'url' => ['/banner-partner'],],
                            ['label' => 'Потерянные места', 'icon' => 'eye-slash', 'url' => ['/banner-place/lost-places'],],
                        ],
                    ],
                    [
                        'label' => 'Инструменты',
                        'icon' => 'briefcase',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Проверка Youtube видео', 'icon' => 'video', 'url' => ['/youtube-missed'],],
                            ['label' => 'Дублирование фоток', 'icon' => 'clone', 'url' => ['/article-photo-hash/double-photos-content'],],
                            ['label' => 'Дублирование превью фоток', 'icon' => 'clone', 'url' => ['/article-photo-hash/double-photos-preview'],],
                            ['label' => 'Орфографические ошибки', 'icon' => 'exclamation-triangle', 'url' => ['/article-spelling/spelling-preview'],],
                            ['label' => 'Ошибки от пользователей', 'icon' => 'exclamation-triangle', 'url' => ['/article-error-info'],],
                            ['label' => 'Ошибки разметки статей', 'icon' => 'code', 'url' => ['/article-html-error'],],
                        ],
                    ],
                    [
                        'label' => 'Администрирование ',
                        'icon' => 'cogs',
                        'active' => true,
                        'items' => [
                            ['label' => 'Пользователи', 'icon' => 'users', 'url' => ['/admin-user']],
                            ['label' => 'Расписание крона', 'icon' => 'clock', 'url' => ['/cron']],
                            ['label' => 'Логи крона', 'icon' => 'clock', 'url' => ['/cron-log', 'page' => 999999]],
                            ['label' => 'Gii', 'icon' => 'edit', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug']],
                            ['label' => 'Очистить кеш assets', 'icon' => 'trash', 'url' => yii\helpers\Url::toRoute(['site/clear-assets'])],
                            ['label' => 'Очистить весь кеш', 'icon' => 'trash', 'url' => yii\helpers\Url::toRoute(['site/clear-cache'])],
                            ['label' => 'Ошибки', 'icon' => 'bug', 'url' => ['/error-log']],
                            ['label' => 'Ответы сервера', 'icon' => 'exchange-alt', 'url' => ['/manual-response']],
                            ['label' => 'Сообщить об ошибке', 'icon' => 'note', 'url' => ['error-log/bug-form']],
                            ['label' => 'Токен для яндекса', 'icon' => 'bug', 'url' => ['site/generate-yandex-token']]
                        ]
                    ],
                ],
            ];

            if(DomainHelper::currentIs(DomainHelper::ALLSLIM)) {

                $menu['items'][] = [
                    'label' => 'Дополнительно',
                    'icon' => 'cogs',
                    'active' => true,
                    'items' => [
                        ['label' => 'Калорийность продуктов', 'icon' => 'note', 'url' => ['/calorie']],
                        ['label' => 'Гликемический индекс', 'icon' => 'note', 'url' => ['/glycemic']],
                        ['label' => 'Консультация диетолога', 'icon' => 'note', 'url' => ['/ask']]
                    ]
                ];
            }

        ?>

        <?= backend\widgets\menu\Menu::widget($menu) ?>

    </section>

</aside>
