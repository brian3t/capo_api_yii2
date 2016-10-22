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
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'first_name',
        'status_code',
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
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
