$(document).ready(function(){

    $('#ckeditor_wrap_button, #seo_wrap_button, #anchor_wrap_button').on('click', function() {
        var plus ='glyphicon-plus';
        var minus ='glyphicon-minus';
        var span = $(this).find('span');
        ($(this).attr('aria-expanded') === 'false') ? span.removeClass(plus).addClass(minus) : span.removeClass(minus).addClass(plus);
    });

    $('#seo_analize_button, #refresh-analysis').on('click', function (e) {
        e.preventDefault();
        var infoArea = $('#js-analysis-info-area');
        var url = $(this).attr('data-url');
        var self = $(this);
        infoArea.html('Проверка...');

         $.get(url, function (data) {
             infoArea.html('Готово');

             $.pjax.reload({container: '#grid_analyse_pjax', async: false});
             if(self.attr('id') == 'seo_analize_button') {
                 $.pjax.reload({container: '#grid_analyse_headers', async: false});
                 $.pjax.reload({container: '#grid_analyse_alts', async: false});
             }
        }).fail(function (data, status, text) {
            if (data.status == 503) {
                infoArea.html('Nopyx временно недоступен. Повторите попытку позже.');
            }
            if (data.status == 500) {
                infoArea.html('Возникла ошибка при получении данных с Nopyx. Проверьте статью вручную или обратитесь к администрации.');
            }
        });
    });

    $('#delImages').on('click', function() {
        var url = $(this).attr('data-url');

        $.get(url, function(data){
            var result = JSON.parse(data);
            if(result.status == true && result.value) {
                $('#thumb-image').attr('src', result['value'])
            }
        })
    });


    $('#article-imagefile').on('change', function () {
        var preview = document.querySelector('#thumb-image');
        var file    = document.querySelector('input[type=file]').files[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
            preview.width = 300;
            preview.height = 200;
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    $('.article-index .export').on('click', function (e) {
        var selectedArticles = $("#article-grid").yiiGridView('getSelectedRows');
        var selectedAll = $(".select-on-check-all:checked").val();

        var uri = '/admin/article/export';
        if(selectedAll == 1) {
             uri += window.location.search;
        } else if(selectedArticles.length > 0) {
            uri += '?articles[]=' + selectedArticles.join('&articles[]=');
        }
        window.location.assign(uri);
    });

    $('.article-export .buffer').on('click', function (e) {
        var input = $('.article-export .urls');

        input.focus().select();
        document.execCommand('copy');

        e.preventDefault();
    });

    $(document).on('click', '#related-yandex-less', function(){
        console.log($('#articlesearch-showlessthen20').val());
        ($('#articlesearch-showlessthen20').val() == 1) ? $('#articlesearch-showlessthen20').val('') : $('#articlesearch-showlessthen20').val(1);
        $('#grid_related_form').submit();
        //$.pjax.reload({container: '#grid_related_pjax', async: false});
    });


// anchors start

    $(document).on('click', '#createAnchor, .update-anchors', function () {
        $('#newAnchor').modal('show').find('.modal-content').load($(this).attr('data-url'));
    });

    $(document).on('click', '.add-new-anchor, .update-new-anchor', function(){
        var form = $('#anchor-form');

        if($(this).hasClass('add-new-anchor')) {
            $('.hidden-anchor-article-id').val($('#createAnchor').attr('data-id'));
        }

        $.post(form.attr('action'), form.serialize())
            .done(function(res){
                if(res == 1){
                    $('#newAnchor').modal('hide');
                    $(form).trigger('reset');
                    $.pjax.reload({container:'#grid_anchors_pjax'});
                }
            })
            .fail(function(){
                console.log('Server error on create or update anchor');
            });
        return false;
    });


    $(document).on('click', '.delete-anchors', function(){
        if(confirm('Вы уверены что хотите удалить?')) {
            $.post($(this).attr('data-url'), $(this).attr('data-id'))
                .done(function(data){
                    if(data == 1) {
                        $.pjax.reload({container:'#grid_anchors_pjax'});
                    }
                })
                .fail(function () {
                    console.log('Server error on delete anchor');
                })
        }
    });

    $(document).on('click', '#by-text-get-anchors', function(){

        if(!confirm('Вы уверены что хотите удалить все анкоры ?')) {
            return;
        }

        $.get($(this).attr('data-url'))
            .done(function(data){
                $('.status-by-text').html(data).show();
                $.pjax.reload({container:'#grid_anchors_pjax'});
            })
    });

// anchors end

    $(document).on('click', '.relatet-inactive', function(){
        $(this).find('span').text(function(i, text){
            return text === 'Показать' ? 'Закрыть' : 'Показать'
        });
        var el = $(this).parent().prev(); el.toggle();
        return false;
    })

    // admin-group-form access
    if ($('.admin-group-form').length) {
        var highlightChecked = function(w){
            if (typeof w == 'undefined') {
                var w = $('.admin-group-form .allow-actions');
            } else {
                w = $(w);
            }

            w.each(function (i, e) {
                if ($('input[type="checkbox"]:checked', e).length) {
                    $(e).addClass('highlight');
                } else {
                    $(e).removeClass('highlight');
                }
            });
        };
        highlightChecked();

        $('.admin-group-form .allow-actions .group-highlight').click(function () {
            var w = $(this).parents('ul')[0];

            $('input[type="checkbox"]:not(.group-highlight)', w).click();
        });

        $('.admin-group-form .allow-actions input[type="checkbox"]').click(function () {
            highlightChecked($(this).parents('.allow-actions'));
        });
    }
});

// page sizes
$(document).on('change', '#page_size_length', function(){
    $('#page_size_form').submit();
});

// popular
$('#popular .removeBannerCode').on('click', function(e){
    e.preventDefault();
    $(this).closest('.control-group ').remove();
});

$('#addPopular').on('click', function(){
    let last = $('.controls input').last();
    let lastId = (last.length > 0) ? last.attr('data-id') : -1;
    let newId = parseInt(lastId) + 1;
    let template = `<div class="control-group ">
                    <label class="control-label required" for="popular_article_${newId}">ID Популярной записи <span class="required">*</span></label>
                    <div class="controls">
                        <input data-id="${newId}" type="text" name="popular_article[${newId}]" value="" id="popular_article_${newId}">
                    </div>
                    <div class="controls"><a href="#" class="removeBannerCode">Удалить запись</a></div>
                </div>`;
    $('#popular').append(template);
});



