if(typeof CKEDITOR != 'undefined') {

    CKEDITOR.plugins.add('review', {
        icons: 'review',
        init: function (editor) {

            editor.addCommand('openWorkSpaceReview', new CKEDITOR.dialogCommand('workspaceDialogReview'));

            editor.ui.addButton('Review', {
                label: 'Вставить Review',
                command: 'openWorkSpaceReview',
                toolbar: 'insert,99',

            });

            CKEDITOR.dialog.add('workspaceDialogReview');

        }
    });

    CKEDITOR.dialog.add( 'workspaceDialogReview', function ( editor ) {
        return {
            title: 'Abbreviation Properties',
            minWidth: 400,
            minHeight: 200,

            contents: [
                {
                    id: 'tab-review',
                    label: 'Вставка отзыва',
                    elements: [
                        {
                            type: 'text',
                            id: 'name',
                            label: 'Имя автор отзыва',
                            validate: CKEDITOR.dialog.validate.notEmpty( "Введите имя." ),
                            default : ''
                        },
                        {
                            type: 'textarea',
                            id: 'content',
                            label: 'Текст отзыва',
                            validate: CKEDITOR.dialog.validate.notEmpty( "Введите текст." ),
                            default : ''
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var reviewName = dialog.getValueOf('tab-review', 'name'),
                    reviewContent = dialog.getValueOf('tab-review', 'content');

                editor.insertText('[review name="' + reviewName +'" content="' + reviewContent +'"]');
            }
        };
    });
}










