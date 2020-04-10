import getElementById from './get-element-by-id';

/**
 * @typedef {Object} ArticlesLoaderInit
 * @property {Function} [beforeFirstLoading] - функция, которая вызывается перед тем как подгрузить
 *     первую статью. Должна вернуть Promise
 * @property {Function} [rewiteSource] - функция, которая перезапишет источник статьи. Можно
 *     использовать для того чтобы добавить какие-то параметры к url
 * @property {Object} selectors
 * @property {String} selectors.article - селектор для поиска статьи.
 * @property {String} selectors.interesting - селектор для поиска элемента с интересными статьями.
 *     Поиск будет производиться в article.
 * @property {String} selectors.sources - селектор для поиска ссылок на интересные статьи. Поиск
 *     будет производиться в элементе interesting.
 */

export class ArticlesLoader {
    /**
     * @constructor
     * @param {ArticlesLoaderInit} options
     */
    constructor({ beforeFirstLoading, rewriteSource, selectors }) {
        this.rewriteSource = rewriteSource;
        this.beforeFirstLoading = beforeFirstLoading || (() => Promise.resolve(null));
        this.selectors = selectors;
        this.current = 0;
        this.articles = [];
        this.sources = [];
        this.addArticle(document.querySelector(selectors.article));

        const observer = new IntersectionObserver(this.OnIntersect.bind(this));
        if (this.interesting) observer.observe(this.interesting);
        observer.observe(this.article);
    }

    /**
     * Добавляет статью в подгрузчик
     * @param {HTMLElement} article
     */
    addArticle(article) {
        this.articles.push({
            article,
            interesting: article.querySelector(this.selectors.interesting),
        });

        const sources = [...this.interesting.querySelectorAll(this.selectors.sources)]
            .map((source) => source.href);
        this.sources = this.sources.concat(sources);

        return this.articles[this.articles.length - 1];
    }

    /**
     * Возвращает статью, которая в данный момент в экране пользователя
     * @returns {HTMLElement}
     */
    get article() {
        return this.articles[this.current].article;
    }

    /**
     * Возвращает элемент с интересными статьями для статьи, которая находится в экране пользователя
     * @returns {HTMLElement}
     */
    get interesting() {
        return this.articles[this.current].interesting;
    }

    /**
     * Вызывается, когда какой-то из элементов попал в область видимости
     * @param {IntersectionObserverEntry} entries
     * @param {IntersectionObserver} observer
     */
    OnIntersect(entries, observer) {
        const intersectingEntry = entries.find((entry) => entry.isIntersecting);
        if (!intersectingEntry) return;

        if (intersectingEntry.target === this.interesting) {
            this.onIntersectInteresting(this.interesting, observer);
            return;
        }

        this.onIntersectArticle(intersectingEntry.target);
    }

    /**
     * Вызывается, когда интересные статьи появляются в области просмотра пользователя
     * @param {HTMLElement} interesting
     * @param {IntersectionObserver} observer
     */
    onIntersectInteresting(interesting, observer) {
        observer.unobserve(interesting);
        if (!this.sources.length) return;
        const source = this.sources.shift();

        this.beforeFirstLoading()
            .then(() => {
                this.beforeFirstLoading = (() => Promise.resolve(null));
                return this.load(source);
            })
            .then((page) => {
                const article = this.append(page, source);
                const newArticleInfo = this.addArticle(article);

                observer.observe(newArticleInfo.article);
                observer.observe(newArticleInfo.interesting);
            })
            .catch(console.error);
    }

    /**
     * Вызывается, когда статья появляется в области просмотра
     * @param {HTMLElement} article
     */
    onIntersectArticle(article) {
        this.current = this.articles.findIndex((articleInfo) => articleInfo.article === article);
        const {title, source} = article.dataset;
        history.replaceState(null, null, source);
        if (title) document.querySelector('title').textContent = title;
    }

    /**
     * Загружает новую статью из указанного источника
     * @param {String} source
     * @returns {Promise<{main: String, title: String}>}
     */
    load(source) {
        const rewritedSource = this.rewriteSource ? this.rewriteSource(source) : source;

        return new Promise((resolve, reject) => {
            fetch(rewritedSource)
                .then((response) => response.text())
                .then((html) => {
                    const main = getElementById(html, 'article-content');
                    const title = html.match(/<title.*?>(.*?)<\/title>/)[1];
                    return resolve({main, title, source});
                })
                .catch(reject);
        });
    }

    /**
     * Добавляет новую статью в документ
     * @param {Object} articleInfo - информация о статье
     * @param {String} articleInfo.main - тело статьи
     * @param {String} articleInfo.source - источник
     * @param {String} articleInfo.title - заголовок документа статьи
     * @param {String} source - источник документа
     * @returns {Article}
     */
    append(articleInfo) {
        const oldArticle = this.articles[this.current].article;
        oldArticle.insertAdjacentHTML('afterend', articleInfo.main);

        const newArticle = oldArticle.nextElementSibling;
        newArticle.dataset.title = articleInfo.title;
        newArticle.dataset.source = articleInfo.source;
        newArticle.classList.add('_ajax');

        document.dispatchEvent(new CustomEvent('append-article', {
            bubbles: false,
            detail: { newArticle, oldArticle },
        }));

        return newArticle;
    }
}
