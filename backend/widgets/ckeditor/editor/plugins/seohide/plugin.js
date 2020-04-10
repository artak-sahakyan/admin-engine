if(typeof CKEDITOR != 'undefined') {

    CKEDITOR.plugins.add('seohide', {
        icons: 'seohide',
        init: function (editor) {

            editor.addCommand('openWorkSpaceSeohide', new CKEDITOR.dialogCommand('workspaceDialogSeohide'));

            editor.ui.addButton('Seohide', {
                label: 'Вставить Seohide',
                command: 'openWorkSpaceSeohide',
                toolbar: 'insert,99',

            });

            CKEDITOR.dialog.add('workspaceDialogSeohide');

        }
    });

    CKEDITOR.dialog.add( 'workspaceDialogSeohide', function ( editor ) {
        return {
            title: 'Abbreviation Properties',
            minWidth: 400,
            minHeight: 200,

            contents: [
                {
                    id: 'tab-basic',
                    label: 'Вставка seohide-ссылки',
                    elements: [
                        {
                            type: 'text',
                            id: 'link',
                            label: 'Ссылка',
                            validate: CKEDITOR.dialog.validate.notEmpty( "Введите ссылку." ),
                            default : 'https://www.google.com'
                        },
                        {
                            type: 'text',
                            id: 'link-text',
                            label: 'Текст',
                            validate: CKEDITOR.dialog.validate.notEmpty( "Введите текст." ),
                            default : 'гугл'
                        }
                    ]
                }
            ],
            onShow: function() {
                // this.setValueOf('tab-basic', 'link-text', editor.getSelection().getSelectedText().toString() );
            },
            onOk: function() {
                var dialog = this;
                var linkText = dialog.getValueOf('tab-basic', 'link-text'),
                    linkHref = dialog.getValueOf('tab-basic', 'link');

                editor.insertHtml('[seohide url="' + linkHref +'" title="' + linkText +'"]');

            }
        };
    });
}










