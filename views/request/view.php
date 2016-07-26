<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title=$model->cuser->getUsername_and_id();
$this->params['breadcrumbs'][]=['label'=>'Request','url'=>['index']];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="request-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Request' . ' ' . Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">

            <?= Html::a('Update',['update','id'=>$model->cuser_id],['class'=>'btn btn-primary']) ?>
            <?= Html::a('Delete',['delete','id'=>$model->cuser_id],[
                'class'=>'btn btn-danger',
                'data'=>[
                    'confirm'=>'Are you sure you want to delete this item?',
                    'method'=>'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn=[
            [
//                'attribute'=>'cuser.id',
                'label'=>'Rider',
                'value'=>$model->cuser->username_and_id
            ],
            'status',
            'pickup_full_address',
            'pickup_lat',
            'pickup_lng',
            'dropoff_full_address',
            'dropoff_lat',
            'dropoff_lng',
        ];
        echo DetailView::widget([
            'model'=>$model,
            'attributes'=>$gridColumn
        ]);
        ?>
    </div>

    <div class="row">
        <?php
        if($providerOffer->totalCount)
        {
            $gridColumnOffer=[
                ['class'=>'yii\grid\SerialColumn'],
                [
                    'attribute'=>'cuser.id',
                    'label'=>'Driver',
                    'value'=>function ($model,$key,$index,$column)
                    {
                        return $model->cuser->username . ' - ' . $model->cuser->id;
                    }
                ],
                [
                    'attribute'=>'request.cuser_id',
                    'label'=>'Rider',
                    'value'=>function ($model,$key,$index,$column)
                    {
                        return $model->requestCuser->username . ' - ' . $model->requestCuser->id;
                    }
                ],
                'status',
            ];
            echo Gridview::widget([
                'dataProvider'=>$providerOffer,
                'pjax'=>true,
                'pjaxSettings'=>['options'=>['id'=>'kv-pjax-container-offer']],
                'panel'=>[
                    'type'=>GridView::TYPE_PRIMARY,
                    'heading'=>'<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Offers for this request'),
                ],
                'columns'=>$gridColumnOffer
            ]);
        }
        ?>
    </div>
</div>