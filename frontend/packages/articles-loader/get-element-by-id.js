/**
 * Вырезает из html элемент с указанным id
 * @param {String} html
 * @returns {String}
 */
export default function getElementById(html, id) {
    const match = html.match(new RegExp(`<[^>]+?id="${id}".*?>`));
    const result = findCloseTag(html, match[0], match.index + match[0].length, match.index + match[0].length);
    return html.slice(match.index, result);
}

function findCloseTag(html, element, openStart, closeStart = 0) {
    const tag = element.match(/^<([a-z-]+)/)[1];
    const openTag = `<${tag}`;
    const closeTag = `</${tag}>`;
    const closeTagPosition = html.indexOf(closeTag, closeStart);
    const openTagPosition = html.indexOf(openTag, openStart + openTag.length);

    if (openTagPosition === -1 || openTagPosition > closeTagPosition) {
        return closeTagPosition + closeTag.length;
    }

    return findCloseTag(
        html,
        element,
        openTagPosition,
        closeTagPosition + closeTag.length
    );
}
