# Подгрузчик статей

## Пример использования

```javascript
import { ArticlesLoader } from 'engine';

if (document.querySelector('.article')) {
    new ArticlesLoader({
        selectors: {
            article: '.page__main',
            interesting: '.maybe-interesting._with-counters',
            sources: '.post-preview',
        },
        rewriteSource: (source) => {
            const url = new URL(source);
            url.searchParams.append('ad', 'dfp');
            return url.toString();
        },
    }));
}
```

## Описание

* **selectors.article** - это селектор по которому подгрузчик сможет найти элемент с
первой статьёй. Внутри этой статьи подгрузчик найдёт *selectors.interesting*.
* **selectors.interesting** - элемент в котором подгрузчик найдёт ссылки на похожие
статьи. Именно эти статьи подгрузчик и будет подгружать, когда пользователь до них
доскроллит.
* **selectors.sources** - подгрузчик будет искать источники для статей внутри
*interesting*. Это должны быть элементы с аттрибутом href.
* **[rewriteSource]** - функция, которая вызовется перед подгрузкой новой статьи.
Функция вызывается с аргументом source - источником (url) подгружаемой статьи.
К источнику можно добавить какие-нибудь параметры или полностью изменить. Например,
на sovets добавляется параметр ?ad=dfp . Если rewriteSource указан, подгрузится
статья по ссылке, которую вернёт эта функция.
* **[beforeFirstLoading]** - функция, которая вызовется перед подгрузкой первой статьи.
Может использоваться для загрузки чего-либо. Например, если реклама от каких-то
источников показывается только в подгруженных статьях, скрипты этой рекламы можно
подгрузить в этой функции. Важно, чтобы эта функция возвращала Promise.

Итак, подгрузчик делает запрос к статье и в ответе приходит полностью весь html, а
нужна только статья. Чтобы подгрузчик разобрался какой элемент ему вырезать и
добавлять, статье нужно указать аттрибут id со значением article-content. Именно
этот элемент будет добавлен после последней статьи.

## Действия после подгрузки

Подгрузчик генерирует событие *append-article* на document, когда подгружает новую
статью, поэтому чтобы сделать что-то с новой или старой статьёй нужно навесить
обработчик на document и уже в нём выполнять все действия. Например:

```javascript
...
document.addEventListener('append-article', (event) => {
    // Добавляет минифутер в конец старой статьи
    const mini = Footer.renderMinifooter();
    event.detail.oldArticle.appendChild(mini);
});
```

```javascript
...
// Удаляет хлебные крошки из новой статьи
document.addEventListener(
    'append-article',
    (event) => Breadcrumbs.removeFrom(event.detail.newArticle),
);
```

**event.detail.oldArticle** - элемент со статьёй, которая идёт перед вновь подгруженной.
**event.detail.newArticle** - элемент с вновь подгруженной статьёй.

> **Важно!** - ArticlesLoader использует внутри себя IntersectionObserver API,
который пока ещё работает не во всех браузерах. поэтому нужно отдельно добавлять
[поллифил](https://www.npmjs.com/package/intersection-observer).
