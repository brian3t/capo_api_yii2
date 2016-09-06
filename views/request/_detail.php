<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

?>
<div class="request-view">

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
        'status',
        'dropoff_full_address',
        'dropoff_lat',
        'dropoff_lng',
        'pickup_full_address',
        'pickup_lat',
        'pickup_lng',
        'trigger_col',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>