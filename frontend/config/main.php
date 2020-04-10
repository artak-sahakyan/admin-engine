<?php

return [
    'controllerNamespace' => 'frontend\controllers',
    'on beforeAction' => function() {
        // Simply protection from https://en.wikipedia.org/wiki/Clickjacking
        header('X-Frame-Options: deny');
    },
];