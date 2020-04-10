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
                      <?= Html::a('<i class="fa fa-chevron-right"></i> Дублированные места', ['/banner-place/double-banner-places'] , ['class' => 'btn btn-default btn-flat']) ?>
                    </span>

                </div>

            </div>               
        </div>
    </div>
</div>
