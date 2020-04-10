/**
 * @typedef {Object} CackleInit
 * @property {Number} id - идентификатор виджета
 * @property {String} url - текущий адрес страницы
 * @property {String} [container] - id элемента с комментариями
 */

export class CackleComments {
    /**
     * Инициализирует комментарии cackle @see http://cackle.me/help/widget-api
     * @param {CackleInit} cackleInit
     * @returns {void}
     */
    constructor(cackleInit, container) {
        this.scriptLoaded = false;
        const observer = new IntersectionObserver((entries) => {
            const intersected = entries.find((entry) => entry.isIntersecting);
            if (!intersected) return;
            observer.unobserve(intersected.target);
            this.loadScript().then(this.show(cackleInit, intersected.target));
        });

        observer.observe(document.querySelector('.cackle-comments'));
        document.addEventListener('append-article', (event) => {
            const comments = event.detail.newArticle.querySelector('.cackle-comments');
            observer.observe(comments);
        });
    }

    /**
     * @private Подгружает скрипт cackle
     * @returns {Promise<void>}
     */
    loadScript() {
        if (this.scriptLoaded) return Promise.resolve();

        return new Promise((resolve, reject) => {
            window.cackle_widget = []; // eslint-disable-line camelcase

            const script = document.createElement('script');
            script.async = true;
            script.onload = resolve;
            script.onerror = reject;
            this.scriptLoaded = true;
            script.src = '//cackle.me/widget.js';
            document.head.appendChild(script);
        });
    }

    /**
     * @private Показывает комментарии
     * @param {CackleInit} cackleInit
     * @param {HTMLElement} container
     */
    show(cackleInit, container) {
        const comments = window.cackle_widget || []; // eslint-disable-line camelcase, no-undef
        const options = Object.assign({}, cackleInit, {
            container: container.id,
            widget: 'Comment',
            url: container.dataset.source,
        });

        comments.push(options);
        if (window.Cackle) window.Cackle.bootstrap(true); // eslint-disable-line no-undef
    }
}
