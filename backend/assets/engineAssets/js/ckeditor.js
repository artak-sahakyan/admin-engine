if(typeof CKEDITOR != 'undefined') {
    CKEDITOR.addCss('.cke_editable {font-size: 17px; font-family: Arial;}');
    CKEDITOR.dtd.$removeEmpty['p'] = true;
    CKEDITOR.dtd.$removeEmpty['div'] = true;

    //config.fillEmptyBlocks = false;

    //  CKEDITOR.plugins.add('seohide', {
    //     icons: 'seohide',
    //     init: function (editor) {
    //
    //         editor.addCommand('openWorkSpaceSeohide', new CKEDITOR.dialogCommand('workspaceDialogSeohide'));
    //
    //         editor.ui.addButton('Seohide', {
    //             label: 'Вставить Seohide',
    //             command: 'openWorkSpaceSeohide',
    //             toolbar: 'insert,99',
    //
    //         });
    //
    //         CKEDITOR.dialog.add('workspaceDialogSeohide');
    //
    //     }
    // });
    //
    // CKEDITOR.dialog.add( 'workspaceDialogSeohide', function ( editor ) {
    //     return {
    //         title: 'Abbreviation Properties',
    //         minWidth: 400,
    //         minHeight: 200,
    //
    //         contents: [
    //             {
    //                 id: 'tab-basic',
    //                 label: 'Вставка seohide-ссылки',
    //                 elements: [
    //                     {
    //                         type: 'text',
    //                         id: 'link',
    //                         label: 'Ссылка',
    //                         validate: CKEDITOR.dialog.validate.notEmpty( "Введите ссылку." ),
    //                         default : 'https://www.google.com'
    //                     },
    //                     {
    //                         type: 'text',
    //                         id: 'link-text',
    //                         label: 'Текст',
    //                         validate: CKEDITOR.dialog.validate.notEmpty( "Введите текст." ),
    //                         default : 'гугл'
    //                     }
    //                 ]
    //             }
    //         ],
    //         onShow: function() {
    //            // this.setValueOf('tab-basic', 'link-text', editor.getSelection().getSelectedText().toString() );
    //         },
    //         onOk: function() {
    //             var dialog = this;
    //             var linkText = dialog.getValueOf('tab-basic', 'link-text'),
    //                 linkHref = dialog.getValueOf('tab-basic', 'link');
    //
    //             editor.insertHtml('[seohide url="' + linkHref +'" title="' + linkText +'"]');
    //
    //         }
    //     };
    // });
}


(async () => {

    const shortcodes = [

        /\[seohide(.+?)\]/g,

        /\[banner(.+?)\]/g,

        /\[turbo(.+?)\]/g,

        /\[sources\](.+?)\[\/sources\]/g,

        /\[videos title="right"\](.+?)\[\/videos\]/g,

        /\[voting(.+?)\]/g,

        /\[article_advertising_place (.+?)\]/g,

    ];



    const editor = await waitEditor();

    const nodes = findNodesWithShortcodes(shortcodes, editor);

    nodes.forEach((node) => highlightShortcodes(node));



    /**

     * Ожидает пока появится элемент с редактором, потом возвращает его

     * @returns {Promise<HTMLElement>}

     */

    function waitEditor() {

        return new Promise((resolve) => {

            const interval = setInterval(() => {

                const editor = document.querySelector('.cke_wysiwyg_frame');

                if (editor) {

                    clearInterval(interval);

                    return resolve(editor.contentWindow.document.body);

                }

            }, 300);

        });

    }

    

    /**

     * Ищет в элементе текст, который подходит под указанные регулярные выражения. Проблема состоит в

     * том, что поиска по тексту ещё не изобрели, поэтому нужно рекурсивно перебрать все элементы, пока

     * не найдутся нужные текста. Возвращает массив node-элементов в которых был найден указанный текст

     * @param {Array<RegExp>} shortcodes

     * @param {HTMLElement} target

     * @returns {Array<Node>}

     */

    function findNodesWithShortcodes(shortcodes, target) {

        const foundNodes = [];

    
        if(target !== null) {
            for (let i = 0; i < target.childNodes.length; i++) {

                const node = target.childNodes[i];



                if (node.nodeType === 3) { // TEXT_NODE

                    if (matchShortcodesInNode(shortcodes, node).length) {

                        foundNodes.push(node);

                    }

                } else if (node.nodeType === 1) {

                    Array.prototype.push.apply(foundNodes, findNodesWithShortcodes(shortcodes, node));

                }

            }
        }

        return foundNodes;

    }

    

    /**

     * Матчит текст с указанными шорткодами

     * @param {Array<Regexp>} shortcodes

     * @param {Node} node

     * @returns {Array<{index: Number, input: String}>}

     */

    function matchShortcodesInNode(shortcodes, node) {

        const matches = [];



        for (let i = 0; i < shortcodes.length; i++) {

            while (result = shortcodes[i].exec(node.textContent)) {

                matches.push({

                    index: result.index,

                    input: result[0],

                });

            }

        }

    

        return matches;

    }

    

    /**

     * Подсвечивает шорткоды в указанном элементе

     * @param {Node} node

     */

    function highlightShortcodes(node) {

        const shortcodesInfo = matchShortcodesInNode(shortcodes, node);

        for (let i = 0; i < shortcodesInfo.length; i++) {

            node.parentElement.innerHTML = node.parentElement.innerHTML.replace(

                shortcodesInfo[i].input,

                `<mark>${shortcodesInfo[i].input}</mark>`,

            );

        }

    }

})();







