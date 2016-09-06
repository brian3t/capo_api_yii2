<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = $model->cuser_id;
$this->params['breadcrumbs'][] = ['label' => 'Request', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Request'.' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
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
        'trigger_col',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
    $gridColumnOffer = [
        ['class' => 'yii\grid\SerialColumn'],
        [
                'attribute' => 'cuser.id',
                'label' => 'Cuser'
        ],
        [
                'attribute' => 'request.cuser_id',
                'label' => 'Request Cuser'
        ],
        'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerOffer,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-offer']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode('Offer'.' '. $this->title),
        ],
        'columns' => $gridColumnOffer
    ]);
?>
    </div>
</div>
