<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cuser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cuser', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuser-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Cuser'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
                        
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'first_name',
        'cuser_status',
        // 'status_code',
        'status_description',
        'commuter',
        'hashed_password',
        'enrolled',
        'email:email',
        'username',
        'commuter_data',
        'lat',
        'lng',
        'address_realtime',
        'apns_device_reg_id',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
if($providerRequest->totalCount){
    $gridColumnRequest = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'cuser_id', 'hidden' => true],
            [
                'attribute' => 'cuser.id',
                'label' => 'Cuser'
        ],
            'status',
            'dropoff_full_address',
            'dropoff_lat',
            'dropoff_lng',
            'pickup_full_address',
            'pickup_lat',
            'pickup_lng',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerRequest,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-request']],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Request'.' '. $this->title),
        ],
        'columns' => $gridColumnRequest
    ]);
}
?>
    </div>
</div>