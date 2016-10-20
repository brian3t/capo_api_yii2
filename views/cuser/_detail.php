<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cuser */

?>
<div class="cuser-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
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
        'apns_device_reg_id',
        'cuser_status',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>