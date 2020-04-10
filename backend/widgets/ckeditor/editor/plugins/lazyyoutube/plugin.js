if(typeof CKEDITOR != 'undefined') {

    CKEDITOR.plugins.add('lazyyoutube', {
        icons: 'lazyyoutube',
        lang: ['ru'],
        init: function (editor) {

            editor.addCommand('openWorkSpaceLazyyoutube', new CKEDITOR.dialogCommand('workspaceDialogLazyYoutube'));

            editor.ui.addButton('LazyYoutube', {
                label: 'Вставить LazyYoutube',
                command: 'openWorkSpaceLazyyoutube',
                toolbar: 'insert,99',

            });

            CKEDITOR.dialog.add('workspaceDialogLazyYoutube');

        }
    });

    CKEDITOR.dialog.add( 'workspaceDialogLazyYoutube', function ( editor ) {
        return {
            title: 'Abbreviation Properties',
            minWidth: 400,
            minHeight: 200,

            contents: [
                {
                    id: 'tab-lazyyoutube',
                    label: 'Insert youtube code',
                    elements: [
                        {
                            type: 'textarea',
                            id: 'htmlCode',
                            label: 'Вставьте HTML-код сюда',
                            validate: function(){
                                if ( !this.getValue() )
                                {
                                    alert( editor.lang.lazyyoutube.noCode );
                                    return false;
                                }
                                else{
                                    var htmlCode = this.getValue();
                                    var id = parseId(htmlCode);
                                    var video = false;

                                    if (id != false) {
                                        video = ytVidId(id);
                                    }

                                    if ( this.getValue().length === 0 ||  video === false)
                                    {
                                        alert( editor.lang.lazyyoutube.invalidEmbed );
                                        return false;
                                    }
                                }
                            },
                            default : ''
                        }
                    ]
                }
            ],
            onOk: function() {
                var dialog = this;
                var lazyYoutubeId = ytVidId(parseId(dialog.getValueOf('tab-lazyyoutube', 'htmlCode')));

                editor.insertText('[lazyyoutube id="' + lazyYoutubeId +'"]');
            }
        };
    });

    /**
     * JavaScript function to match (and return) the video Id
     * of any valid Youtube Url, given as input string.
     * @author: Stephan Schmitz <eyecatchup@gmail.com>
     * @url: http://stackoverflow.com/a/10315969/624466
     */
    function ytVidId( url )
    {
        var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
        return ( url.match( p ) ) ? RegExp.$1 : false;
    }

    function parseId( code ) {
        return code.match(/src="(.+?)"/) ? RegExp.$1 : false;
    }
}










