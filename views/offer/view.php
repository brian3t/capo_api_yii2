<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */

$this->title = $model->cuser_id;
$this->params['breadcrumbs'][] = ['label' => 'Offer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Offer from'.' '. Html::encode($model->cuser->getUsername_and_id()) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
                        
            <?= Html::a('Update', ['update', 'id' => $model->cuser_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->cuser_id], [
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
        [
            'attribute' => 'cuser.id',
            'label' => 'Driver',
            'value'=>$model->cuser->username
        ],
        [
            'attribute' => 'request_cuser',
            'label' => 'Rider',
            'value'=>$model->requestCuser->getUsername_and_id()
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