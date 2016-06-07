<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cuser */

$this->title = 'Create Cuser';
$this->params['breadcrumbs'][] = ['label' => 'Cuser', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuser-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
