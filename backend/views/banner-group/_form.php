<?php

use yii\widgets\ActiveForm;
use common\models\ArticleCategory;
use common\widgets\ButtonGroupWidget;
?>

<div class="banner-group-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
        	<div class="form-group">
   				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="checkbox">
    			<?= $form->field($model, 'show_default_group')->checkBox(['selected' => $model->show_default_group]) ?>
    		</div>
    	</div>
	</div>

	<div class="row">
        <div class="col-md-4">
            <div class="form-group">
				<label class="control-label">Добавить урлы из категории</label>
				<select id="article_category_id" class="form-control" name="article_category_id" onchange="$.get(&quot;/admin/article/children-list?id=&quot;+$(this).val(),function(data){$(&quot;#article-category_child_id&quot;).html(data)})">
					<?php foreach(ArticleCategory::getRootCategoriesList() as $key => $value): ?>
						<option value="<?= $key ?>"><?= $value ?></option>
					<?php endforeach; ?>
				</select>
			</div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
        		<a id="addArticlesFromCategory" class="btn btn-primary">Добавить</a>
        	</div>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-8">
        	<div class="form-group">
            <textarea name="articles" id="articles" class="form-control" rows="11" aria-invalid="false"><?php foreach($model->articles as $article): ?><?=  'http://'.$_SERVER['SERVER_NAME'].'/'.$article->id.'-'.$article->slug.'.html
' ?><?php endforeach; ?></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    <label class="control-label">Статьи найденные в существующих группах</label>
                    <table id="doubleGroups" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                        <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>Заголовок</th>
                                <th>Группа</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4">Ничего не найдено</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
		<div class="col-md-12">
        	<?= ButtonGroupWidget::widget(['model' => $model]) ?>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>


<script type="text/javascript">
  
    var articles = null;

    window.onload = function() {
        articles = $('#articles');

        articles.on('input',function() {
            getDoubleGroups();
        });

        $('#addArticlesFromCategory').on('click', function() {
            getArticlesFromCategory();
        });
    };

    function getDoubleGroups() {
        var url         = '<?= \yii\helpers\Url::toRoute(['banner-group/get-double-groups']) ?>';
        var urlList     = articles.val();

        if(urlList) {
            $.post(
                url, {urls: urlList}, function(data) {
                var data = jQuery.parseJSON(data);
                $('#doubleGroups tbody').find('tr').remove().end();

                if(data.length > 0) {
                    $.each(data,function(key, value) {
                        $('#doubleGroups tbody').append(
                            '<tr role="row" class="even">'+
                                '<td>'+value.id+'</td>'+
                                '<td>'+value.title+'</td>'+
                                '<td>'+value.groupName+'</td>'+
                                '<td>'+
                                    '<button onclick="acceptArticle('+value.id+')" type="button" class="btn btn-success btn-xs">'+
                                        '<span class="fa fa-check"></span>'+
                                    '</button> '+
                                    '<button onclick="cancelArticle('+value.id+')" type="button" class="btn btn-danger btn-xs">'+
                                        '<span class="fa fa-times"></span>'+
                                    '</button>'+
                                '</td>'+
                            '</tr>');
                    });
                }
                else {
                    $('#doubleGroups tbody').append('<tr><td colspan="4">Ничего не найдено</td></tr>');
                }
                
            });
        }
    }

    function getArticlesFromCategory() {
        var url         = '<?= \yii\helpers\Url::toRoute(['banner-group/get-articles-from-category']) ?>';
        var category_id = $('#article_category_id').val();

        $.post(
            url, {category_id: category_id}, function(data) {
                var data = jQuery.parseJSON(data);

                if (confirm('Удалить уже внесенные урлы?')) { articles.val(''); }

                $.each(data,function(key, value) {
                    articles.val(articles.val() + value + '\n');
                });

                getDoubleGroups();
            });
    }

    function acceptArticle(article_id) {
        if(article_id) {
            var url = '<?= \yii\helpers\Url::toRoute(['banner-group/accept-article']) ?>';

            $.post(
                url, {article_id: article_id}, function(data) {
                getDoubleGroups();
            });
        }
    }

    function cancelArticle(article_id) {
        var url         = '<?= \yii\helpers\Url::toRoute(['banner-group/cancel-article']) ?>';
        var urlList     = articles.val();

        if(article_id && urlList) {
            $.post(
                url, {article_id: article_id, urls: urlList}, function(data) {
                    articles.val(data);
                    getDoubleGroups();
                }
            );
        }
    }
</script>
