<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;     
?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">
            <div class="col-md-4">
                
                <div class="input-group">
                    <span class="input-group-btn">
                      <?= Html::a('<i class="fa fa-chevron-left"></i> Орфографические ошибки', ['/article-spelling/spelling-preview'] , ['class' => 'btn btn-default btn-flat']) ?>
                    </span>
                    <span class="input-group-btn">
                    <?= Html::a('Добавить слово-исключение', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </span>
                </div>
            </div>               
        </div>
    </div>
</div>

<?php if($data['wordCount'] && !$data['running']): ?>
<div class="alert alert-info" role="alert">
    <?="Добавлени  новых {$data['wordCount']} слов, затрагивает {$data['total']} статей, запусть проверку?"?>
    <?= Html::a('Запустить', ['#'], ['class' => 'btn btn-success btn-flat', 'style' => 'margin-left:15px;text-decoration:none', 'id' => 'run-update-word', 'data-url'=> \yii\helpers\Url::to(['update-words'])]) ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php
    $this->registerJs("
        $('#run-update-word').on('click', function(e) {
            e.preventDefault();
            $(this).alert('close');
            let url = $(this).attr('data-url');
            location.href = url;
        });
    ");
?>
