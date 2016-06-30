<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */

$this->title = 'Update Offer: ' . ' ' . $model->cuser_id;
$this->params['breadcrumbs'][] = ['label' => 'Offer', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cuser_id, 'url' => ['view', 'id' => $model->cuser_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
