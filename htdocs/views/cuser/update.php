<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cuser */

$this->title = 'Update Cuser: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cuser', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
