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
            <h2><?= 'Offer'.' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        [
                'attribute' => 'cuser.id',
                'label' => 'Cuser'
        ],
        [
                'attribute' => 'request.cuser_id',
                'label' => 'Request Cuser'
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
