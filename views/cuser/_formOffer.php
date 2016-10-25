<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="cuser-form">

    <?= $form->field($Offer, 'status')->dropDownList([ 'pending' => 'Pending', 'inactive' => 'Inactive', 'accepted' => 'Accepted', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>

</div>
