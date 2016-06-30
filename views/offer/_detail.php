<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */

?>
<div class="offer-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->cuser_id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        [
            'attribute' => 'cuser.id',
            'label' => 'Cuser',
        ],
        [
            'attribute' => 'request.cuser_id',
            'label' => 'Request Cuser',
        ],
        'created_at',
        'updated_at',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>